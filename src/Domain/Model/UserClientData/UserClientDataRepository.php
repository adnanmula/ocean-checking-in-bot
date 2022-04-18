<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\UserClientData;

use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserClientDataRepository
{
    public function byUserId(Uuid $id): ?UserClientData;
    public function save(UserClientData $clientData): void;
}
