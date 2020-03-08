<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSettings\Exception;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\ExistsException;

final class UserAlreadyHasSettings extends ExistsException
{
    public function __construct()
    {
        parent::__construct('User already has settings.');
    }
}
