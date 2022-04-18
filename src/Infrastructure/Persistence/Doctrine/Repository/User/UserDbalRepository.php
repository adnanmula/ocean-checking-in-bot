<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\User;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIn;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInMode;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserId;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSchedule;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSettings;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;
use AdnanMula\ClockInBot\Util\Json;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserDbalRepository extends DbalRepository implements UserRepository
{
    private const TABLE_USER = 'users';

    public function byId(Uuid $id): ?User
    {
        $result = $this->connection
            ->createQueryBuilder()
            ->select('a.id, a.reference, a.username, a.settings, a.clientData, a.schedule')
            ->from(self::TABLE_USER, 'a')
            ->where('a.reference = :reference')
            ->setParameter('id', $id->value())
            ->setMaxResults(1)
            ->execute()
            ->fetchAssociative();

        if (false === $result) {
            return null;
        }

        return $this->map($result);
    }

    public function byReference(string $reference): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select('a.id, a.reference, a.username, a.settings, a.clientData, a.schedule')
            ->from(self::TABLE_USER, 'a')
            ->where('a.reference = :reference')
            ->setParameter('reference', $reference)
            ->setMaxResults(1)
            ->execute()
            ->fetchAssociative();

        if (false === $result) {
            return null;
        }

        return $this->map($result);
    }

    public function save(User $user): void
    {
        $stmt = $this->connection->prepare(
            \sprintf(
                '
                INSERT INTO %s (id, reference, username) VALUES (
                    :id, :reference, :username
                ) ON CONFLICT (id) DO UPDATE SET
                    id = :id, reference = :reference, username = :username',
                self::TABLE_USER,
            ),
        );

        $stmt->bindValue(':id', $user->id()->value());
        $stmt->bindValue(':reference', $user->reference());
        $stmt->bindValue(':username', $user->username());

        $stmt->execute();
    }

    private function map($user): User
    {
        $rawSettings = Json::decode($user['settings']);
        $rawClientData = Json::decode($user['client_data']);
        $rawSchedule = Json::decode($user['schedule']);

        return User::create(
            Uuid::from($user['id']),
            $user['reference'],
            $user['username'],
            UserSettings::from(
                ClockInPlatform::from($rawSettings['platform']),
                ClockInMode::from($rawSettings['mode']),
            ),
            UserClientData::from($rawClientData),
            UserSchedule::from(...array_map(
                static fn (array $clockIn) => ClockIn::from($clockIn['time'], $clockIn['randomnes']),
                $rawSchedule,
            )),
        );
    }
}
