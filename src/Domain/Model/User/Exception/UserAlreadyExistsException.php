<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Exception;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\ExistsException;

final class UserAlreadyExistsException extends ExistsException
{
    public function __construct()
    {
        parent::__construct('User already exists.');
    }
}
