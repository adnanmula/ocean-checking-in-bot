<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\ValueObject;

use Pccomponentes\Ddd\Domain\Model\ValueObject\CollectionValueObject;

final class ClockIns extends CollectionValueObject
{
    /** @param array<ClockIn> $checkIns */
    public static function from(array $checkIns): self
    {
        self::assert($checkIns);

        return self::from($checkIns);
    }

    /** @param array<ClockIn> $checkIns */
    private static function assert(array $checkIns): void
    {
        \array_walk($checkIns, static function ($checkIn): void {
            if (false === $checkIn instanceof ClockIn) {
                throw new \InvalidArgumentException('Invalid element type.');
            }
        });
    }
}
