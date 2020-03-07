<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Fixtures\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInData;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\User;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use DemigrantSoft\ClockInBot\Domain\Service\Persistence\Fixture;
use DemigrantSoft\ClockInBot\Infrastructure\Fixtures\DbalFixture;

final class UserFixtures extends DbalFixture implements Fixture
{
    private const TABLE_USER = 'users';

    public const FIXTURE_USER_1_ID = '426117e9-e016-4f53-be1f-4eb8711ce625';
    public const FIXTURE_USER_2_ID = '97a7e9fe-ff27-4d52-83c0-df4bc9309fb0';

    private bool $loaded = false;

    public function load(): void
    {
        $this->save(
            User::create(
                UserId::from(self::FIXTURE_USER_1_ID),
                UserReference::from('123456'),
                UserUsername::from('username'),
                UserSettings::from(
                    ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                    ClockInMode::from(ClockInMode::MODE_MANUAL),
                    ClockInSchedule::from(),
                    ClockInData::from(),
                )
            )
        );

        $this->save(
            User::create(
                UserId::from(self::FIXTURE_USER_2_ID),
                UserReference::from('100000'),
                UserUsername::from('username2'),
                UserSettings::from(
                    ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                    ClockInMode::from(ClockInMode::MODE_MANUAL),
                    ClockInSchedule::from(),
                    ClockInData::from(),
                )
            )
        );

        $this->loaded = true;
    }

    private function save(User $user): void
    {
        $stmt = $this->connection->prepare(
            \sprintf(
                '
                INSERT INTO %s (id, reference, username, password) VALUES (
                    :id, :reference, :username, :password
                ) ON CONFLICT (id) DO UPDATE SET
                    id = :id, reference = :reference, username = :username, password = :password',
                self::TABLE_USER
            )
        );

        $stmt->bindValue(':id', $user->aggregateId()->value());
        $stmt->bindValue(':reference', $user->reference()->value());
        $stmt->bindValue(':username', $user->username()->value());
        $stmt->bindValue(':password', $user->password()->value());

        $stmt->execute();
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function dependants(): array
    {
        return [];
    }
}
