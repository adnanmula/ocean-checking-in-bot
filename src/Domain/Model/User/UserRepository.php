<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserRepository
{
    public function byId(Uuid $id): ?User;
    public function byReference(UserReference $reference): ?User;
    public function save(User $user): void;
}
