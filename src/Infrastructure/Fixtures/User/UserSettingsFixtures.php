<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Fixtures\User;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInMode;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserId;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSettings;
use AdnanMula\ClockInBot\Domain\Service\Persistence\Fixture;
use AdnanMula\ClockInBot\Infrastructure\Fixtures\DbalFixture;

final class UserSettingsFixtures extends DbalFixture implements Fixture
{
    private const TABLE_USER_SETTINGS = 'user_settings';

    private bool $loaded = false;

    public function load(): void
    {
        $this->save(
            UserSettings::create(
                UserId::from(UserFixtures::FIXTURE_USER_1_ID),
                ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                ClockInMode::from(ClockInMode::MODE_MANUAL),
            ),
        );

        $this->save(
            UserSettings::create(
                UserId::from(UserFixtures::FIXTURE_USER_2_ID),
                ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                ClockInMode::from(ClockInMode::MODE_AUTO),
            ),
        );

        $this->loaded = true;
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function dependants(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    private function save(UserSettings $settings): void
    {
        $stmt = $this->connection->prepare(
            \sprintf(
                '
                INSERT INTO %s (user_id, platform, mode) VALUES (
                    :user_id, :platform, :mode
                ) ON CONFLICT (user_id) DO UPDATE SET
                    user_id = :user_id, platform = :platform, mode = :mode',
                self::TABLE_USER_SETTINGS,
            ),
        );

        $stmt->bindValue(':user_id', $settings->userId()->value());
        $stmt->bindValue(':platform', $settings->platform()->value());
        $stmt->bindValue(':mode', $settings->mode()->value());

        $stmt->execute();
    }
}
