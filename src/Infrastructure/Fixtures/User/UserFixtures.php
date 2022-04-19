<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Fixtures\User;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserId;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use AdnanMula\ClockInBot\Domain\Service\Persistence\Fixture;
use AdnanMula\ClockInBot\Infrastructure\Fixtures\DbalFixture;
use AdnanMula\ClockInBot\Util\Json;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserFixtures extends DbalFixture implements Fixture
{
    public const FIXTURE_USER_1_ID = '426117e9-e016-4f53-be1f-4eb8711ce625';
    public const FIXTURE_USER_2_ID = '97a7e9fe-ff27-4d52-83c0-df4bc9309fb0';

    private const TABLE_USER = 'users';

    private bool $loaded = false;

    public function load(): void
    {
        $this->save(
            User::create(
                Uuid::from(self::FIXTURE_USER_1_ID),
                '123456',
                'username',
                null,
                null,
                null,
            ),
        );

        $this->save(
            User::create(
                Uuid::from(self::FIXTURE_USER_2_ID),
                '100000',
                'username2',
                null,
                null,
                null,
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
        return [];
    }

    private function save(User $user): void
    {
        $stmt = $this->connection->prepare(
            \sprintf(
                '
                INSERT INTO %s (id, reference, username, settings, client_data, schedule) VALUES (
                    :id, :reference, :username, :settings, :client_data, :schedule
                ) ON CONFLICT (id) DO UPDATE SET
                    reference = :reference,
                    username = :username,
                    settings = :settings,
                    client_data = :client_data,
                    schedule = :schedule
              ',
                self::TABLE_USER,
            ),
        );

        $stmt->bindValue(':id', $user->id()->value());
        $stmt->bindValue(':reference', $user->reference());
        $stmt->bindValue(':username', $user->username());
        $stmt->bindValue(':settings', null);
        $stmt->bindValue(':client_data', Json::encode([]));
        $stmt->bindValue(':schedule', null);

        $stmt->execute();
    }
}
