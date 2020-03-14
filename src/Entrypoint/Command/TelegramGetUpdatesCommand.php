<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TelegramGetUpdatesCommand extends Command
{
    private const COMMANDS = [
        '/register' => 'command.user.register',
        '/setup' => 'command.user.setup',
    ];

    private string $botToken;
    private PsrContainerInterface $container;

    public function __construct(string $botToken, PsrContainerInterface $container)
    {
        $this->botToken = $botToken;
        $this->container = $container;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setDescription('Process telegram messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        dd('hi');

//        $telegram = new \Telegram($this->botToken);
        $msg = '/register test tsts';

        $this->handle($this->parseCommand($msg));

        return 0;
    }

    private function parseCommand(string $msg): array
    {
        $arguments = \explode(' ', $msg);

        if (false === \in_array($arguments[0], \array_keys(self::COMMANDS))) {
            throw new \InvalidArgumentException('Invalid command ' . $arguments[0]);
        }

        $command = \array_shift($arguments);

        return [$command => $arguments];
    }

    private function handle(array $args): void
    {
        $command = self::COMMANDS[\key($args)];
        $arguments = $args[$command];

        $handler = $this->container->get($command);
    }
}
