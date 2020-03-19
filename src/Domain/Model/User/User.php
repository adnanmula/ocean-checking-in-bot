<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\SimpleAggregateRoot;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class User extends SimpleAggregateRoot
{
    private const MODEL_NAME = 'user';

    private UserReference $reference;
    private UserUsername $username;

    public static function create(Uuid $id, UserReference $reference, UserUsername $username): self
    {
        $self = new self($id);
        $self->reference = $reference;
        $self->username = $username;

        return $self;
    }

    public function reference(): UserReference
    {
        return $this->reference;
    }

    public function username(): UserUsername
    {
        return $this->username;
    }

    public static function modelName(): string
    {
        return self::MODEL_NAME;
    }
}
