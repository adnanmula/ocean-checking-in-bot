<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSchedule;

use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\ValueObject\ClockIn;
use Pccomponentes\Ddd\Domain\Model\ValueObject\ValueObject;

final class UserSchedule
{
    /** @var array<ClockIn>  */
    private array $clockIns;

    private function __construct(array $clockIns)
    {
        $this->clockIns = $clockIns;
    }

    public static function from(ClockIn ...$checkIn)
    {
        return new self($checkIn);
    }
}
