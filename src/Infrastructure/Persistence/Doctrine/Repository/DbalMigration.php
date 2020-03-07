<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository;

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
        $this->connection->exec('
          CREATE TABLE users (
                id uuid NOT NULL,
                reference character varying(16) NOT NULL
                    CONSTRAINT reference_unique UNIQUE,
                username character varying(16) NOT NULL,
                PRIMARY KEY(id)
            )'
        );

        $this->connection->exec('
          CREATE TABLE user_settings (
                user_id uuid NOT NULL,
                platform character varying(16) NOT NULL,
                mode character varying(16) NOT NULL,
                PRIMARY KEY(user_id)
            )'
        );

        $this->connection->exec('
            ALTER TABLE ONLY user_settings
                ADD CONSTRAINT user_settings_users_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE CASCADE;
        ');

        $this->connection->exec('
          CREATE TABLE user_client_data (
                user_id uuid NOT NULL,
                key character varying(16) NOT NULL,
                value character varying(32) NOT NULL,
                PRIMARY KEY(user_id)
            )'
        );

        $this->connection->exec('
            ALTER TABLE ONLY user_client_data
                ADD CONSTRAINT user_client_data_users_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE CASCADE;
        ');

//        $this->connection->exec('CREATE TABLE "not-working-days" (date TEXT NOT NULL)');
    }

    public function down(): void
    {
//        $this->connection->exec('DROP TABLE IF EXISTS "not-working-days"');
        $this->connection->exec('DROP TABLE IF EXISTS "user_client_data"');
        $this->connection->exec('DROP TABLE IF EXISTS "user_settings"');
        $this->connection->exec('DROP TABLE IF EXISTS "users"');
    }
}
