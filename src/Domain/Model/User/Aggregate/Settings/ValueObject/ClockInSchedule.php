<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject;

use DemigrantSoft\ClockInBot\Model\CheckIn\ClockIn;

final class ClockInSchedule
{
    public static function from(ClockIn ...$checkIn)
    {
        return new self();
    }
}
