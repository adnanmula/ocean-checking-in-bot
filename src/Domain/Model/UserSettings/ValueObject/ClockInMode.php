<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject;

use Pccomponentes\Ddd\Domain\Model\ValueObject\EnumValueObject;

final class ClockInMode extends EnumValueObject
{
    public const MODE_AUTO = 'auto';
    public const MODE_MANUAL = 'manual';
    public const MODE_NOTIFICATION = 'notification';

    protected static $allowedValues = [self::MODE_AUTO, self::MODE_MANUAL, self::MODE_NOTIFICATION];

    public function isAuto(): bool
    {
        return $this->value() === self::MODE_AUTO;
    }

    public function isManual(): bool
    {
        return $this->value() === self::MODE_MANUAL;
    }

    public function isNotification(): bool
    {
        return $this->value() === self::MODE_NOTIFICATION;
    }
}
