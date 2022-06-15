<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\Exception;

use AdnanMula\ClockInBot\Domain\Model\Shared\Exception\LogicException;

final class PlatformNotSupportedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Clock in platform is not supported');
    }
}
