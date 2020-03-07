<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Exception\UserNotExistsException;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserRepository;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;

final class UserFinder
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserId $id)
    {
        $user = $this->repository->byId($id);

        if (null === $user) {
            throw new UserNotExistsException();
        }

        return $user;
    }
}
