<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject\Ocean;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;
use Symfony\Component\HttpFoundation\Response;

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
        $currentKeys = self::PARAMETERS;

        foreach ($data as $key => $datum) {
            if (false === \in_array($key, $currentKeys)) {
                throw new \InvalidArgumentException('Invalid client data.', Response::HTTP_BAD_REQUEST);
            }

            $keyPosition = \array_search($key, $currentKeys);
            unset($currentKeys[$keyPosition]);
        }

        if (\count($currentKeys) > 0) {
            throw new \InvalidArgumentException('Invalid client data.', Response::HTTP_BAD_REQUEST);
        }
    }
}
