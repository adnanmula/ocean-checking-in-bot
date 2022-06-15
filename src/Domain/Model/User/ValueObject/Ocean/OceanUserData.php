<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject\Ocean;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;

final class OceanUserData extends UserClientData
{
    public const PARAMETERS = ['lat', 'lon', 'baseurl', 'user', 'pass'];

    static function from(array $data): UserClientData
    {
        self::assert($data);

        return new self($data);
    }

    private static function assert(array $data): void
    {
//        TODO
    }
}
