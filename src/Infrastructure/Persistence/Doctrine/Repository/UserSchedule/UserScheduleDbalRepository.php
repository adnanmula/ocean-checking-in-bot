<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\UserSchedule;

use AdnanMula\ClockInBot\Domain\Model\UserSchedule\UserSchedule;
use AdnanMula\ClockInBot\Domain\Model\UserSchedule\UserScheduleRepository;
use AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserScheduleDbalRepository extends DbalRepository implements UserScheduleRepository
{
    public function byUserId(Uuid $id): ?UserSchedule
    {
        // TODO: Implement byUserId() method.
    }

    public function save(UserSchedule $schedule): void
    {
        // TODO: Implement save() method.
    }
}
