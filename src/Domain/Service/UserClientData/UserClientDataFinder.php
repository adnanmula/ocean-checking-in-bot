<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\Exception\UserHasNotClientData;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;

final class UserClientDataFinder
{
    private UserClientDataRepository $repository;

    public function __construct(UserClientDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserId $id)
    {
        $data = $this->repository->byUserId($id);

        if (null === $data) {
            throw new UserHasNotClientData();
        }

        return $data;
    }
}
