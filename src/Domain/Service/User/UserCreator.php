<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Exception\UserAlreadyExistsException;
use DemigrantSoft\ClockInBot\Domain\Model\User\User;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserRepository;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;

final class UserCreator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserId $id, UserReference $reference, UserUsername $username, UserSettings $settings): void
    {
        $user = $this->repository->byReference($reference);

        if (null !== $user) {
            throw new UserAlreadyExistsException();
        }

        $this->repository->save(
            User::create($id, $reference, $username, $settings)
        );
    }
}
