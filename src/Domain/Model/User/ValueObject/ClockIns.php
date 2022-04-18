<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

use AdnanMula\ClockInBot\Domain\Model\Shared\ValueObject\CollectionValueObject;

final class ClockIns extends CollectionValueObject
{
    /** @param array<ClockIn> $clockIns */
    public static function from(...$clockIns): static
    {
        self::assert($clockIns);

        return self::from($clockIns);
    }

    /** @param array<ClockIn> $clockIns */
    private static function assert(array $clockIns): void
    {
        \array_walk($clockIns, static function ($clockIn): void {
            if (false === $clockIn instanceof ClockIn) {
                throw new \InvalidArgumentException('Invalid element type.');
            }
        });
    }
}
