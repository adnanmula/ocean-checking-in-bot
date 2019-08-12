<?php declare(strict_types=1);

namespace App\Infrastructure\NotWorkingDays\Repository;

use App\Domain\NotWorkingDays\Repository\NotWorkingDaysRepository;
use App\Infrastructure\SqliteRepository;

class NotWorkingDaysSqliteRepository extends SqliteRepository implements NotWorkingDaysRepository
{
    public function add(\DateTimeInterface $date): void
    {
        $stmt = $this->connection->prepare('INSERT into not_working_days (date) values (:date)');
        $stmt->bindValue('date', $date->format('Y-m-d'), SQLITE3_TEXT);

        $stmt->execute();
    }

    public function all(): array
    {
        $days = $this->connection->query('SELECT date FROM not_working_days');

        $notWorkingDays = [];
        while ($day = $days->fetchArray(SQLITE3_ASSOC)) {
            $notWorkingDays[] = $day['date'];
        }

        return $notWorkingDays;
    }

    public function check(\DateTimeInterface $date): bool
    {
        $stmt = $this->connection->prepare('SELECT date FROM not_working_days WHERE date = :date');
        $stmt->bindValue('date', $date->format('Y-m-d'), SQLITE3_TEXT);
        $result = $stmt->execute();

        return $result->fetchArray() ? true : false;
    }
}
