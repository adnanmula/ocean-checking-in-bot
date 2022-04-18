<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\UserClientData;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;
use AdnanMula\ClockInBot\Domain\Model\UserClientData\Exception\UserHasNotClientData;
use AdnanMula\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

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
