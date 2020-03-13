<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientData;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserClientDataCreator
{
    private UserClientDataRepository $repository;

    public function __construct(UserClientDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $userId, array $newData): void
    {
        $oldData = $this->repository->byUserId($userId);

        $this->repository->save(
            UserClientData::from($userId, \array_merge($oldData->all(), $newData))
        );
    }
}
