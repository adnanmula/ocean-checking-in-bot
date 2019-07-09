<?php declare(strict_types=1);

namespace App\Infrastructure;

class SqliteRepository
{
    protected $connection;

    public function __construct(string $url)
    {
        $this->connection = new \SQLite3($url);
    }
}
