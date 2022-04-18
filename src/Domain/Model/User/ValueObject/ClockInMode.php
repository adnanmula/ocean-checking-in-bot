<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

use PcComponentes\Ddd\Domain\Model\ValueObject\EnumValueObject;

final class ClockInMode extends EnumValueObject
{
    public const MODE_AUTO = 'auto';
    public const MODE_MANUAL = 'manual';
    public const MODE_NOTIFICATION = 'notification';

    protected static $allowedValues = [self::MODE_AUTO, self::MODE_MANUAL, self::MODE_NOTIFICATION];

    public function isAuto(): bool
    {
        return self::MODE_AUTO === $this->value();
    }

    public function isManual(): bool
    {
        return self::MODE_MANUAL === $this->value();
    }

    public function isNotification(): bool
    {
        return self::MODE_NOTIFICATION === $this->value();
    }
}
