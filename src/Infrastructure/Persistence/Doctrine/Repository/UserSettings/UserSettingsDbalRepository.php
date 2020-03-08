<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;

final class UserSettingsDbalRepository extends DbalRepository implements UserSettingsRepository
{
    private const TABLE_USER_SETTINGS = 'user_settings';

    public function byUserId(UserId $userId): ?UserSettings
    {
        // TODO: Implement byUserId() method.
    }

    public function save(UserSettings $settings): void
    {
        // TODO: Implement save() method.
    }

    private function map($settings): UserSettings
    {
        return UserSettings::from(
            UserId::from($settings['user_id']),
            ClockInPlatform::from($settings['platform']),
            ClockInMode::from($settings['mode']),
            );
    }
}
