<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Exception;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class UserNotExistsException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('User not exists.');
    }
}
