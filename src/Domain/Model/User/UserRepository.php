<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;

interface UserRepository
{
    public function byId(UserId $id): ?User;
    public function byReference(UserReference $reference): ?User;
    public function save(User $user): void;
}
