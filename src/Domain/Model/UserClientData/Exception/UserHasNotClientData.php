<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\UserClientData\Exception;

use AdnanMula\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class UserHasNotClientData extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('User has not client data.');
    }
}
