<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;

interface UserClientDataRepository
{
    public function byUserId(UserId $id): ?UserClientData;
    public function save(UserClientData $clientData): void;
}
