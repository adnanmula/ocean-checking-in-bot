<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\Exception\UserAlreadyHasSettings;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserSettingsCreator
{
    private UserSettingsRepository $repository;

    public function __construct(UserSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $userId, ClockInPlatform $platform, ClockInMode $mode): void
    {
        $settings = $this->repository->byUserId($userId);

        if (null !== $settings) {
            throw new UserAlreadyHasSettings();
        }

        $this->repository->save(
            UserSettings::create($userId, $platform, $mode),
        );
    }
}