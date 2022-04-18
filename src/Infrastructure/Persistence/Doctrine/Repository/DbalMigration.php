<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository;

use AdnanMula\ClockInBot\Domain\Service\Persistence\Migration;
use Doctrine\DBAL\Connection;

abstract class DbalMigration implements Migration
{
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    abstract public function up(): void;

    abstract public function down(): void;
}
