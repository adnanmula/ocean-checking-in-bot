<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Persistence\Migration;

class SqliteMigration implements Migration
{
    protected $connection;

    public function __construct(string $url)
    {
        $this->connection = new \SQLite3($url);
    }

    public function up(): void
    {
        $this->connection->exec('CREATE TABLE "not-working-days" (date TEXT NOT NULL)');
    }

    public function down(): void
    {
        $this->connection->exec('DROP TABLE IF EXISTS "not-working-days"');
    }
}
