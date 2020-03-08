<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\Exception\UserHasNotSettings;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;

final class UserSettingsFinder
{
    private UserSettingsRepository $repository;

    public function __construct(UserSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserId $id)
    {
        $user = $this->repository->byUserId($id);

        if (null === $user) {
            throw new UserHasNotSettings();
        }

        return $user;
    }
}
