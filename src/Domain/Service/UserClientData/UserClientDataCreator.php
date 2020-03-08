<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;

final class UserClientDataCreator
{
    private UserClientDataRepository $repository;

    public function __construct(UserClientDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserId $userId, array ...$data): void
    {
        $data = $this->repository->byUserId($userId);

        //TODO
        foreach ($data->all() as $item) {

        }
    }
}
