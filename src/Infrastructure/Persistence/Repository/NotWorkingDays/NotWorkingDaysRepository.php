<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository\NotWorkingDays;

use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository\DbalRepository;
use DemigrantSoft\ClockInBot\Model\NotWorkingDay\NotWorkingDaysRepository as NotWorkingDaysRepositoryInterface;

final class NotWorkingDaysRepository extends DbalRepository implements NotWorkingDaysRepositoryInterface
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
