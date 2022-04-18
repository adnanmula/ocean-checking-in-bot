<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Tests\Mock\Domain\Model\User;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserObjectMother
{
    private Uuid $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->reference = 'reference';
        $this->username = 'username';
    }

    public function build(): User
    {
        return User::create($this->id, $this->reference, $this->username, null, null, null);
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
