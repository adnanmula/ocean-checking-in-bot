<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject;

use DemigrantSoft\ClockInBot\Model\CheckIn\CheckIn;

final class ClockInSchedule
{
    public static function from(CheckIn ...$checkIn)
    {
        return new self();
    }
}
