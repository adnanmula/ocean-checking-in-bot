<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\CheckIn;

use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\CollectionValueObject;

final class CheckIns extends CollectionValueObject
{
    /** @param array<CheckIn> $checkIns */
    public static function from(array $checkIns): self
    {
        self::assert($checkIns);

        return self::from($checkIns);
    }

    /** @param array<CheckIn> $checkIns */
    private static function assert(array $checkIns): void
    {
        \array_walk($checkIns, function ($checkIn): void {
            if (false === $checkIn instanceof CheckIn) {
//                throw new algo
            }
        });
    }
}
