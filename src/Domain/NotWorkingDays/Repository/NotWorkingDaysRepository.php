<?php declare(strict_types=1);

namespace App\Domain\NotWorkingDays\Repository;

interface NotWorkingDaysRepository
{
    public function add(\DateTimeInterface $date): void;
    public function all(): array;
    public function check(\DateTimeInterface $date): bool;
}
