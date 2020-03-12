<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserSchedule;

use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\UserSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\UserScheduleRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserScheduleFinderByUserId
{
    private UserScheduleRepository $repository;

    public function __construct(UserScheduleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $id): UserSchedule
    {
        $data = $this->repository->byUserId($id);

        if (null === $data) {
//            throw new ();
        }

        return $data;
    }
}
