<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Util;

final class Json
{
    public static function encode(array $array): string
    {
        return \json_encode($array, \JSON_THROW_ON_ERROR, 512);
    }

    public static function decode(string $json): array
    {
        return \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
    }
}
