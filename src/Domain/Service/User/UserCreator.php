<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\User;

use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserAlreadyExistsException;
use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserCreator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $id, string $reference, string $username): void
    {
        $user = $this->repository->byReference($reference);

        if (null !== $user) {
            throw new UserAlreadyExistsException();
        }

        $this->repository->save(
            User::create($id, $reference, $username, null, null, null),
        );
    }
}
