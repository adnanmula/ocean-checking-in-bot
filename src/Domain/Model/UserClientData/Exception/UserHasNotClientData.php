<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserClientData\Exception;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class UserHasNotClientData extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('User has not client data.');
    }
}
