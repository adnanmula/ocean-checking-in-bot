<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TelegramGetUpdatesCommand extends Command
{
    private const COMMANDS = [
        '/register',
        '/setup',
    ];

    private string $botToken;

    public function __construct(string $botToken)
    {
        $this->botToken = $botToken;

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

        return 0;
    }

    private function parseCommand(string $msg): array
    {
        $arguments = \explode(' ', $msg);

        if (\in_array($arguments[0], self::COMMANDS)) {
//            throw new
        }

        return [
            $arguments[0] => \array_shift($arguments),
        ];
    }
}
