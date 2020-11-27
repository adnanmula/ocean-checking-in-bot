<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Application\Command\User\Register\UserRegisterCommand;
use DemigrantSoft\ClockInBot\Application\Command\User\SetUp\UserSetUpCommand;
use DemigrantSoft\ClockInBot\Application\Query\User\GetClockIns\GetClockInsQuery;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;
use Pccomponentes\Ddd\Util\Message\SimpleMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class TelegramGetUpdatesCommand extends Command
{
    private string $botToken;
    private MessageBusInterface $bus;
    private array $telegramCommands;

    public function __construct(string $botToken, MessageBusInterface $bus, array $telegramCommands)
    {
        $this->botToken = $botToken;
        $this->bus = $bus;
        $this->telegramCommands = $telegramCommands;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setDescription('Process telegram messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new \Telegram($this->botToken);

        $client->getUpdates();
        for ($i = 0; $i < $client->UpdateCount(); $i++) {
            $client->serveUpdate($i);

            $this->bus->dispatch($this->getCommand($client->ChatID(), $client->Text()));
        }

        return 0;
    }

    private function getCommand(string $reference, array $text): SimpleMessage
    {
        $arguments = \explode(' ', $text['text']);

        $command = \array_shift($arguments);

        switch (true) {
            case \in_array($command, $this->telegramCommands[UserRegisterCommand::class], true):
                return $this->registerCommand($reference, $arguments);
            case \in_array($command, $this->telegramCommands[UserSetUpCommand::class], true)
                && 0 !== \count($arguments):
                return $this->setUpCommand($reference, $arguments);
            case \in_array($command, $this->telegramCommands[GetClockInsQuery::class], true):
                return $this->getClockInsCommand($reference, $arguments);
        }

        throw new \InvalidArgumentException('Invalid command');
    }

    private function registerCommand(string $reference, array $arguments): SimpleMessage
    {
        return UserRegisterCommand::fromPayload(
            Uuid::v4(),
            [
                UserRegisterCommand::PAYLOAD_ID => $reference,
                UserRegisterCommand::PAYLOAD_USERNAME => $arguments[0],
                UserRegisterCommand::PAYLOAD_REFERENCE => $arguments[1],
            ],
        );
    }

    private function setUpCommand(string $reference, array $arguments): SimpleMessage
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

    private function getClockInsCommand(string $reference, array $arguments): SimpleMessage
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
}
