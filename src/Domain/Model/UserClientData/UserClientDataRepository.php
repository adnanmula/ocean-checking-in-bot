<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserClientData;

use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserClientDataRepository
{
    public function byUserId(Uuid $id): ?UserClientData;
    public function save(UserClientData $clientData): void;
}
