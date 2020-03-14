<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Service\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

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
