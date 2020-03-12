<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Exception\UserNotExistsException;
use DemigrantSoft\ClockInBot\Domain\Model\User\User;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserFinder
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $id): User
    {
        $user = $this->repository->byId($id);

        if (null === $user) {
            throw new UserNotExistsException();
        }

        return $user;
    }
}
