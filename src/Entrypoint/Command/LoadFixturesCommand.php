<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Entrypoint\Command;

use AdnanMula\ClockInBot\Infrastructure\Fixtures\FixturesRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LoadFixturesCommand extends Command
{
    private FixturesRegistry $registry;
    private Connection $connection;

    public function __construct(FixturesRegistry $registry, Connection $connection)
    {
        $this->registry = $registry;
        $this->connection = $connection;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setName('clock-in-bot:environment:fixtures')
            ->setDescription('Load fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tables = $this->connection->getSchemaManager()->listTables();

        \array_walk(
            $tables,
            fn (Table $table) => $this->connection->executeQuery('TRUNCATE "' . $table->getName() . '" CASCADE'),
        );

        $this->registry->execute();

        return self::SUCCESS;
    }
}
