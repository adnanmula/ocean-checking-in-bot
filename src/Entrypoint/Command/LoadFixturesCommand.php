<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Infrastructure\Fixtures\FixturesRegistry;
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
        $this->setDescription('Load fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        \array_walk(
            $this->connection->getSchemaManager()->listTables(),
            fn (Table $table) => $this->connection->executeQuery('TRUNCATE "' . $table->getName() . '" CASCADE'),
        );

        $this->registry->execute();

        return 0;
    }
}
