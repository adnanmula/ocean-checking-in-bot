<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\User;

use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserNotExistsException;
use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

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
