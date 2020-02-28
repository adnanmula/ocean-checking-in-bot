<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository;

use DemigrantSoft\ClockInBot\Domain\Service\Persistence\Migration;
use Doctrine\DBAL\Connection;

final class DbalMigration implements Migration
{
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function up(): void
    {
//        $this->connection->exec('
//          CREATE TABLE users (
//                id uuid NOT NULL,
//                email character varying(128) NOT NULL,
//                password character varying(128) NOT NULL,
//                PRIMARY KEY(id)
//            )'
//        );

        $this->connection->exec('CREATE TABLE "not-working-days" (date TEXT NOT NULL)');
    }

    public function down(): void
    {
        $this->connection->exec('DROP TABLE IF EXISTS "not-working-days"');
//        $this->connection->exec('DROP TABLE IF EXISTS "users"');
    }
}
