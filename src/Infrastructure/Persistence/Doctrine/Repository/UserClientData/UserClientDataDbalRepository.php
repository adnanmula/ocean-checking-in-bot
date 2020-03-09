<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientData;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;

final class UserClientDataDbalRepository extends DbalRepository implements UserClientDataRepository
{
    private const TABLE_USER_SETTINGS = 'user_settings';

    public function byUserId(UserId $id): ?UserClientData
    {
        // TODO: Implement byUserId() method.
    }

    public function save(UserClientData $clientData): void
    {
        // TODO: Implement save() method.
    }

    private function map($user): UserClientData
    {
        //TODO
    }
}
