<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Entrypoint\Command;

use AdnanMula\ClockInBot\Application\Command\User\ManualClockIn\UserManualClockInCommand;
use AdnanMula\ClockInBot\Application\Command\User\Register\UserRegisterCommand;
use AdnanMula\ClockInBot\Application\Command\User\SetUp\UserSetUpCommand;
use AdnanMula\ClockInBot\Application\Query\User\GetClockIns\GetClockInsQuery;
use AdnanMula\ClockInBot\Infrastructure\Service\Telegram\TelegramClient;
use AdnanMula\ClockInBot\Infrastructure\Service\Telegram\TelegramUpdate;
use Assert\AssertionFailedException;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use PcComponentes\Ddd\Util\Message\SimpleMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class TelegramGetUpdatesCommand extends Command
{
    private TelegramClient $telegramClient;
    private MessageBusInterface $bus;
    private array $telegramCommands;

    public function __construct(TelegramClient $telegramClient, MessageBusInterface $bus, array $telegramCommands)
    {
        $this->telegramClient = $telegramClient;
        $this->bus = $bus;
        $this->telegramCommands = $telegramCommands;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setName('clock-in-bot:telegram:update')
            ->setDescription('Process telegram messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->telegramClient->getUpdates() as $update) {
            if (false === $update->isCommand()) {
                continue;
            }

            try {
                dump($this->getCommand($update));
                $this->bus->dispatch($this->getCommand($update));
            } catch (AssertionFailedException $exception) {
                dump('failed: ' . $exception->getMessage());
                continue;
            } catch (\InvalidArgumentException $exception) {
                dump('not command: ' . $update->command());
                continue;
            }



            $this->telegramClient->sendMessage($update->chatId(), 'Done.');
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
            case \in_array($command, $this->telegramCommands[UserSetUpCommand::class], true) && 0 !== \count($arguments):
                return $this->setUpCommand((string) $update->chatId(), $arguments);
            case \in_array($command, $this->telegramCommands[GetClockInsQuery::class], true):
                return $this->getClockInsCommand((string) $update->chatId(), $arguments);
            case \in_array($command, $this->telegramCommands[UserManualClockInCommand::class], true):
                return $this->getManualClockInCommand((string) $update->chatId());
        }

        throw new \InvalidArgumentException('Invalid command');
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
        return UserSetUpCommand::fromPayload(
            Uuid::v4(),
            [
                UserSetUpCommand::PAYLOAD_REFERENCE => $reference,
                UserSetUpCommand::PAYLOAD_PLATFORM => $arguments[0],
                UserSetUpCommand::PAYLOAD_DATA => [
                    'key' => $arguments[1],
                    'key2' => $arguments[2],
                ],
            ],
        );
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
}
