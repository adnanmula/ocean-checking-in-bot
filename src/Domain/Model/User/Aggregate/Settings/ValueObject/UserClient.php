<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject;

use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\EnumValueObject;
use DemigrantSoft\ClockInBot\Infrastructure\Service\CheckIn\Ocean\OceanClient;

final class UserClient extends EnumValueObject
{
    public const CLIENT_OCEAN = OceanClient::PLATFORM;

    protected static $allowedValues = [self::CLIENT_OCEAN];

    public function isOcean(): bool
    {
        return $this->value() === self::CLIENT_OCEAN;
    }
}
