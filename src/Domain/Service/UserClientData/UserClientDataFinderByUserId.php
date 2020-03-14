<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\Exception\UserHasNotClientData;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientData;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserClientDataFinderByUserId
{
    private UserClientDataRepository $repository;

    public function __construct(UserClientDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $id): UserClientData
    {
        $data = $this->repository->byUserId($id);

        if (null === $data) {
            throw new UserHasNotClientData();
        }

        return $data;
    }
}
