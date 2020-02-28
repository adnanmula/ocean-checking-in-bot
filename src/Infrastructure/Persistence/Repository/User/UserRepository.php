<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository\User;

use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Model\User\User;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository\DbalRepository;

final class UserRepository extends DbalRepository
{
    public function byId(Uuid $id): User
    {
        
    }
}
