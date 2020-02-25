<?php declare(strict_types=1);

namespace DemigrantSoft\Infrastructure;

class SqliteRepository
{
    protected \SQLite3 $connection;

    public function __construct(string $url)
    {
        $this->connection = new \SQLite3($url);
    }
}
