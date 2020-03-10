<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Tests\Mock\Domain\Model\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\User;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;

final class UserMockProvider
{
    private UserId $id;
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
        return User::create(
            $this->id,
            $this->reference,
            $this->username
        );
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
