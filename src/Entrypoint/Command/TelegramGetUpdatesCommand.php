<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Entrypoint\Command;

use AdnanMula\ClockInBot\Application\Command\User\ManualClockIn\UserManualClockInCommand;
use AdnanMula\ClockInBot\Application\Command\User\Register\UserRegisterCommand;
use AdnanMula\ClockInBot\Application\Command\User\Remove\UserRemoveCommand;
use AdnanMula\ClockInBot\Application\Command\User\SetUp\UserSetUpCommand;
use AdnanMula\ClockInBot\Application\Query\User\GetClockIns\GetClockInsQuery;
use AdnanMula\ClockInBot\Application\Query\User\GetHelp\GetHelpQuery;
use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserAlreadyExistsException;
use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserNotExistsException;
use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserSetupPendingException;
use AdnanMula\ClockInBot\Infrastructure\Service\Telegram\InvalidTelegramCommand;
use AdnanMula\ClockInBot\Infrastructure\Service\Telegram\TelegramClient;
use AdnanMula\ClockInBot\Infrastructure\Service\Telegram\TelegramUpdate;
use Assert\AssertionFailedException;
use PcComponentes\Ddd\Application\Query;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use PcComponentes\Ddd\Util\Message\SimpleMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class TelegramGetUpdatesCommand extends Command
{
    private TelegramClient $telegramClient;
    private MessageBusInterface $bus;
    private array $telegramCommands;

    public function __construct(
        TelegramClient $telegramClient,
        MessageBusInterface $bus,
        array $telegramCommands
    ) {
        $this->telegramClient = $telegramClient;
        $this->bus = $bus;
        $this->telegramCommands = $telegramCommands;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setName('bot:telegram:update')
            ->setDescription('Process telegram messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->telegramClient->getUpdates() as $update) {
            if (false === $update->isCommand()) {
                continue;
            }

            try {
                $message = $this->getCommand($update);

                if ($message instanceof Query) {
                    $this->telegramClient->sendMessage(
                        $update->chatId(),
                        $this->extractResult($this->bus->dispatch($message)),
                    );
                } else {
                    $this->bus->dispatch($message);

                    $this->telegramClient->sendMessage(
                        $update->chatId(),
                        'Done!',
                    );
                }
            } catch (AssertionFailedException $exception) {
                $this->telegramClient->sendMessage($update->chatId(), 'Invalid arguments.');
                continue;
            } catch (InvalidTelegramCommand $exception) {
                $this->telegramClient->sendMessage($update->chatId(), 'Unknown command.');
                continue;
            } catch (UserSetupPendingException $exception) {
                $this->telegramClient->sendMessage($update->chatId(), 'Set up pending, use the command /help Ocean.');
                continue;
            } catch (UserNotExistsException $exception) {
                $this->telegramClient->sendMessage($update->chatId(), 'User not found.');
                continue;
            } catch (UserAlreadyExistsException $exception) {
                $this->telegramClient->sendMessage($update->chatId(), 'You are already registered.');
                continue;
            } catch (\InvalidArgumentException $exception) {
                $this->telegramClient->sendMessage($update->chatId(), 'Invalid request parameters.');
                continue;
            }
        }

        return self::SUCCESS;
    }

    private function getCommand(TelegramUpdate $update): SimpleMessage
    {
        $arguments = $update->commandArguments();
        $command = \substr($update->command(), 1, \strlen($update->command()));

        switch (true) {
            case \in_array($command, $this->telegramCommands[UserRegisterCommand::class], true):
                return $this->registerCommand((string) $update->chatId(), $update->username());
            case \in_array($command, $this->telegramCommands[UserSetUpCommand::class], true):
                return $this->setUpCommand((string) $update->chatId(), $arguments);
            case \in_array($command, $this->telegramCommands[GetClockInsQuery::class], true):
                return $this->getClockInsCommand((string) $update->chatId(), $arguments);
            case \in_array($command, $this->telegramCommands[UserManualClockInCommand::class], true):
                return $this->getManualClockInCommand((string) $update->chatId());
            case \in_array($command, $this->telegramCommands[GetHelpQuery::class], true):
                return $this->getHelpQuery($arguments);
            case \in_array($command, $this->telegramCommands[UserRemoveCommand::class], true):
                return $this->getRemoveCommand((string) $update->chatId());
        }

        throw new InvalidTelegramCommand();
    }

    private function registerCommand(string $reference, string $username): UserRegisterCommand
    {
        return UserRegisterCommand::fromPayload(
            Uuid::v4(),
            [
                UserRegisterCommand::PAYLOAD_REFERENCE => $reference,
                UserRegisterCommand::PAYLOAD_USERNAME => $username,
            ],
        );
    }

    private function setUpCommand(string $reference, array $arguments): UserSetUpCommand
    {
        $platform = $arguments[0];

        \array_shift($arguments);

        $arguments = $this->argumentsToArray($arguments);

        return UserSetUpCommand::fromPayload(
            Uuid::v4(),
            [
                UserSetUpCommand::PAYLOAD_REFERENCE => $reference,
                UserSetUpCommand::PAYLOAD_PLATFORM => $platform,
                UserSetUpCommand::PAYLOAD_PARAMETERS => $arguments,
            ],
        );
    }

    public function argumentsToArray(array $arguments): array
    {
        $result = [];

        foreach ($arguments as $argument) {
            $arg = \explode('=', $argument);
            $result[$arg[0]] = $arg[1];
        }

        return $result;
    }

    private function getClockInsCommand(string $reference, array $arguments): GetClockInsQuery
    {
        return GetClockInsQuery::fromPayload(
            Uuid::v4(),
            [
                GetClockInsQuery::PAYLOAD_REFERENCE => $reference,
                GetClockInsQuery::PAYLOAD_FROM => $arguments[0] ?? null,
                GetClockInsQuery::PAYLOAD_TO => $arguments[1] ?? null,
            ],
        );
    }

    private function getManualClockInCommand(string $reference): UserManualClockInCommand
    {
        return UserManualClockInCommand::fromPayload(
            Uuid::v4(),
            [
                UserManualClockInCommand::PAYLOAD_REFERENCE => $reference,
            ],
        );
    }

    private function getHelpQuery(array $arguments): GetHelpQuery
    {
        return GetHelpQuery::fromPayload(
            Uuid::v4(),
            [
                GetHelpQuery::PAYLOAD_PLATFORM => $arguments[0] ?? '',
            ],
        );
    }

    private function getRemoveCommand(string $reference): UserRemoveCommand
    {
        return UserRemoveCommand::fromPayload(
            Uuid::v4(),
            [
                UserRemoveCommand::PAYLOAD_REFERENCE => $reference,
            ],
        );
    }

    private function extractResult(mixed $message): mixed
    {
        $stamp = $message->last(HandledStamp::class);

        if (null === $stamp) {
            return null;
        }

        return $stamp->getResult();
    }
}
