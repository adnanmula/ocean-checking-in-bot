<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserRepository
{
    public function byId(Uuid $id): ?User;
    public function byReference(UserReference $reference): ?User;
    public function save(User $user): void;
}
