<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User;

use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserRepository
{
    public function byId(Uuid $id): ?User;
    public function byReference(string $reference): ?User;
    public function save(User $user): void;
}
