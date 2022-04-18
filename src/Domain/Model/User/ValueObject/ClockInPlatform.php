<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

use AdnanMula\ClockInBot\Infrastructure\Service\ClockIn\Ocean\OceanClient;
use PcComponentes\Ddd\Domain\Model\ValueObject\EnumValueObject;

final class ClockInPlatform extends EnumValueObject
{
    public const PLATFORM_OCEAN = OceanClient::PLATFORM;

    protected static $allowedValues = [self::PLATFORM_OCEAN];

    public function isOcean(): bool
    {
        return self::PLATFORM_OCEAN === $this->value();
    }
}
