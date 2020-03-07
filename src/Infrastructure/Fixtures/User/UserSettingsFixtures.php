<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Fixtures\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInData;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Service\Persistence\Fixture;
use DemigrantSoft\ClockInBot\Infrastructure\Fixtures\DbalFixture;

final class UserSettingsFixtures extends DbalFixture implements Fixture
{
    private const TABLE_USER_SETTINGS = 'user_settings';

    private bool $loaded = false;

    public function load(): void
    {
        $this->save(
            UserId::from(UserFixtures::FIXTURE_USER_1_ID),
            UserSettings::from(
                ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                ClockInMode::from(ClockInMode::MODE_MANUAL),
                ClockInSchedule::from(),
                ClockInData::from(),
            )
        );

        $this->save(
            UserId::from(UserFixtures::FIXTURE_USER_2_ID),
            UserSettings::from(
                ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                ClockInMode::from(ClockInMode::MODE_AUTO),
                ClockInSchedule::from(),
                ClockInData::from(),
            )
        );

        $this->loaded = true;
    }

    private function save(UserId $userId, UserSettings $settings): void
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

        $stmt->bindValue(':user_id', $userId->value());
        $stmt->bindValue(':platform', $settings->client()->value());
        $stmt->bindValue(':mode', $settings->mode()->value());

        $stmt->execute();
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function dependants(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
