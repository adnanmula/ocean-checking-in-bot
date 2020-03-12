<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettingsRepository;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserSettingsDbalRepository extends DbalRepository implements UserSettingsRepository
{
    private const TABLE_USER_SETTINGS = 'user_settings';

    public function byUserId(Uuid $userId): ?UserSettings
    {
        $result = $this->connection
            ->createQueryBuilder()
            ->select('a.user_id, a.platform, a.mode')
            ->from(self::TABLE_USER_SETTINGS, 'a')
            ->where('a.user_id = :user_id')
            ->setParameter('user_id', $userId->value())
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        if (false === $result) {
            return null;
        }

        return $this->map($result);
    }

    public function save(UserSettings $settings): void
    {
        $stmt = $this->connection->prepare(
            \sprintf(
                '
                INSERT INTO %s (user_id, platform, mode) VALUES (
                    :user_id, :platform, :mode
                ) ON CONFLICT (user_id) DO UPDATE SET
                    user_id = :user_id, platform = :platform, mode = :mode',
                self::TABLE_USER_SETTINGS
            )
        );

        $stmt->bindValue(':user_id', $settings->userId()->value());
        $stmt->bindValue(':platform', $settings->platform()->value());
        $stmt->bindValue(':mode', $settings->mode()->value());

        $stmt->execute();
    }

    private function map($settings): UserSettings
    {
        return UserSettings::create(
            Uuid::from($settings['user_id']),
            ClockInPlatform::from($settings['platform']),
            ClockInMode::from($settings['mode']),
        );
    }
}
