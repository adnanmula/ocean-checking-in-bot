<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Migrations\Postgresql;

use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalMigration;

final class V001 extends DbalMigration
{
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
                PRIMARY KEY(user_id, key)
            )'
        );
    }

    public function down(): void
    {
        $this->connection->exec('DROP TABLE IF EXISTS "user_client_data"');
        $this->connection->exec('DROP TABLE IF EXISTS "user_settings"');
        $this->connection->exec('DROP TABLE IF EXISTS "users"');
    }
}
