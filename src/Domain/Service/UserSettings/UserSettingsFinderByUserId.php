<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\UserSettings;

use AdnanMula\ClockInBot\Domain\Model\UserSettings\Exception\UserHasNotSettings;
use AdnanMula\ClockInBot\Domain\Model\UserSettings\UserSettings;
use AdnanMula\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserSettingsFinderByUserId
{
    private UserSettingsRepository $repository;

    public function __construct(UserSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $id): UserSettings
    {
        $user = $this->repository->byUserId($id);

        if (null === $user) {
            throw new UserHasNotSettings();
        }

        return $user;
    }
}
