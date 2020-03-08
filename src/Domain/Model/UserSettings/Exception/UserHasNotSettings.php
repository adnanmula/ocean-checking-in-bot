<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSettings\Exception;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class UserHasNotSettings extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('User doesn\'t have settings.');
    }
}
