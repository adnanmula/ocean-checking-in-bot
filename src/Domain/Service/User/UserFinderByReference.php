<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\User;

use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserNotExistsException;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;

final class UserFinderByReference
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserReference $reference)
    {
        $user = $this->repository->byReference($reference);

        if (null === $user) {
            throw new UserNotExistsException();
        }

        return $user;
    }
}
