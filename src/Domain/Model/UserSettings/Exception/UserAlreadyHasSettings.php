<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\UserSettings\Exception;

use AdnanMula\ClockInBot\Domain\Model\Shared\Exception\ExistsException;

final class UserAlreadyHasSettings extends ExistsException
{
    public function __construct()
    {
        parent::__construct('User already has settings.');
    }
}
