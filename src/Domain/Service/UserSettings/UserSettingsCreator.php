<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\UserSettings;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInMode;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSettings;
use AdnanMula\ClockInBot\Domain\Model\UserSettings\Exception\UserAlreadyHasSettings;
use AdnanMula\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

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
