<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\Exception;

use AdnanMula\ClockInBot\Domain\Model\Shared\Exception\LogicException;

final class UserSetupPendingException extends LogicException
{
    public function __construct()
    {
        parent::__construct('User is not set up');
    }
}
