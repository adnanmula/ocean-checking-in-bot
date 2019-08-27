<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Persistence\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InitEnvironmentCommand extends Command
{
    private $migrations;

    public function __construct(Migration ...$migration)
    {
        parent::__construct(null);

        $this->migrations = $migration;
    }

    protected function configure(): void
    {
        $this->setDescription('Environment init');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->migrations as $migration) {
            $migration->down();
            $migration->up();

            $output->writeln(get_class($migration) . ' executed');
        }
    }
}