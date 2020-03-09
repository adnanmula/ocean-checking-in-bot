<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\ValueObject;

use Pccomponentes\Ddd\Domain\Model\ValueObject\IntValueObject;
use Symfony\Component\HttpFoundation\Response;

final class ClockInRandomness extends IntValueObject
{
    public const MAX_RANDOMNESS = 600;

    public static function from(int $value)
    {
        if ($value < 0 || $value > self::MAX_RANDOMNESS) {
            throw new \InvalidArgumentException($value.' exceeds max randomness allowed('.self::MAX_RANDOMNESS.')', Response::HTTP_CONFLICT);
        }

        return parent::from($value);
    }
}
