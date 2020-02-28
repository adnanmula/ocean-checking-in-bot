<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\NotWorkingDay;

use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;

interface NotWorkingDaysRepository
{
    public function add(Uuid $userId, \DateTimeInterface $date): void;
    public function all(Uuid $userId): array;
    public function check(Uuid $userId, \DateTimeInterface $date): bool;
}
