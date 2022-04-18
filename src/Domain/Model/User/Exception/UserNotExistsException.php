<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\Exception;

use AdnanMula\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class UserNotExistsException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('User not exists.');
    }
}
