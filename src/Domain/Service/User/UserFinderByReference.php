<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Exception\UserNotExistsException;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserScheduleRepository;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;

final class UserFinder
{
    private UserScheduleRepository $repository;

    public function __construct(UserScheduleRepository $repository)
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
