<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Tests\Mock\Domain\Model\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientData;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserClientDataMockProvider
{
    private Uuid $userId;
    private array $data;

    public function __construct()
    {
        $this->userId = Uuid::v4();
        $this->data = [];
    }

    public function build(): UserClientData
    {
        return UserClientData::from(
            $this->userId,
            $this->data
        );
    }

    public function addData(array $data)
    {
        $this->data = \array_merge($this->data, $data);
    }

    public function setUserId(Uuid $id): self
    {
        $this->userId = $id;

        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
