<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\UserSettings;

use AdnanMula\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserSettingsRemoverByUserId
{
    private UserSettingsRepository $repository;

    public function __construct(UserSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Uuid $userId): void
    {
        $settings = $this->repository->byUserId($userId);

        if (null !== $settings) {
            $this->repository->removeByUserId($userId);
        }
    }
}
