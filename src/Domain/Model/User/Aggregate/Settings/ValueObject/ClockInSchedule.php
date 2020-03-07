<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject;

use DemigrantSoft\ClockInBot\Domain\Model\ClockIn\ClockIn;
use Pccomponentes\Ddd\Domain\Model\ValueObject\ValueObject;

final class ClockInSchedule implements ValueObject
{
    public static function from(ClockIn ...$checkIn)
    {
        return new self();
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
