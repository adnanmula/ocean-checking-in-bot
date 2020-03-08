<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSchedule;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;

interface UserScheduleRepository
{
    public function byUserId(UserId $id): ?UserSchedule;
    public function save(UserSchedule $schedule): void;
}
