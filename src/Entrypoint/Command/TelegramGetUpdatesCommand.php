<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Application\Command\User\Register\UserRegisterCommand;
use DemigrantSoft\ClockInBot\Application\Command\User\SetUp\UserSetUpCommand;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Pccomponentes\Ddd\Application\Command as LeCommand;

final class TelegramGetUpdatesCommand extends Command
{
    private string $botToken;
    private MessageBusInterface $bus;

    public function __construct(string $botToken, MessageBusInterface $bus)
    {
        $this->botToken = $botToken;
        $this->bus = $bus;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setDescription('Process telegram messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Todo telegram stuff
        new \Telegram($this->botToken);

        $msg = [
            'text' => '/register test tsts',
            'id' => '',
        ];

        $this->bus->dispatch($this->getCommand($msg));

        return 0;
    }

    private function getCommand(array $msg): LeCommand
    {
        $reference = $msg['reference'];
        $arguments = \explode(' ', $msg['text']);

        $command = \array_shift($arguments);

        switch ($command) {
            case '/register':
                return $this->registerCommand($reference, $arguments);
            case '/setup':
                return $this->setUpCommand($reference, $arguments);
        }

        throw new \InvalidArgumentException('Invalid command');
    }

    private function registerCommand(string $reference, array $arguments): LeCommand
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

    private function setUpCommand(string $reference, array $arguments): LeCommand
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
}
