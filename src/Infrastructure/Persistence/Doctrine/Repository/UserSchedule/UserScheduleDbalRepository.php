<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\UserSchedule;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\UserSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\UserScheduleRepository;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;

final class UserScheduleDbalRepository extends DbalRepository implements UserScheduleRepository
{
    public function byUserId(UserId $id): ?UserSchedule
    {
        // TODO: Implement byUserId() method.
    }

    public function save(UserSchedule $schedule): void
    {
        // TODO: Implement save() method.
    }
}
