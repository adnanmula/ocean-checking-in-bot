<?php declare(strict_types=1);

namespace DemigrantSoft\Domain\NotWorkingDays\Repository;

interface NotWorkingDaysRepository
{
    public function add(\DateTimeInterface $date): void;
    /** @return string[] */
    public function all(): array;
    public function check(\DateTimeInterface $date): bool;
}
