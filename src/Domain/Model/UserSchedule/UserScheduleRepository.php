<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSchedule;

use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserScheduleRepository
{
    public function byUserId(Uuid $id): ?UserSchedule;
    public function save(UserSchedule $schedule): void;
}
