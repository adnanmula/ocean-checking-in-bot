<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Tests\Mock\Domain\Model\User;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserId;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserMockProvider
{
    private Uuid $id;
    private UserReference $reference;
    private UserUsername $username;

    public function __construct()
    {
        $this->id = UserId::v4();
        $this->reference = UserReference::from('reference');
        $this->username = UserUsername::from('username');
    }

    public function build(): User
    {
        return User::create($this->id, $this->reference, $this->username);
    }

    public function setId(UserId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setReference(UserReference $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function setUsername(UserUsername $username): self
    {
        $this->username = $username;

        return $this;
    }
}
