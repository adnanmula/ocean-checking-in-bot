<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\NotWorkingDays;

use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;
use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Model\NotWorkingDay\NotWorkingDaysRepository;

final class NotWorkingDaysDbalRepository extends DbalRepository implements NotWorkingDaysRepository
{
    public function add(Uuid $userId, \DateTimeInterface $date): void
    {
        // TODO: Implement add() method.
    }

    public function all(Uuid $userId): array
    {
        // TODO: Implement all() method.
    }

    public function check(Uuid $userId, \DateTimeInterface $date): bool
    {
        // TODO: Implement check() method.
    }
}
