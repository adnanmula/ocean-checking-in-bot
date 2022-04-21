<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\User;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIn;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInMode;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSchedule;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSettings;
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
            ->select('a.id, a.reference, a.username, a.settings, a.client_data, a.schedule')
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
            ->select('a.id, a.reference, a.username, a.settings, a.client_data, a.schedule')
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
                    INSERT INTO %s (id, reference, username, settings, client_data, schedule)
                    VALUES (:id, :reference, :username, :settings, :client_data, :schedule)
                    ON CONFLICT (id) DO UPDATE SET
                    id = :id,
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
        $stmt->bindValue(':settings', null === $user->settings() ? null : Json::encode($user->settings()->jsonSerialize()));
        $stmt->bindValue(':client_data', null === $user->clientData()? null : Json::encode($user->clientData()->jsonSerialize()));
        $stmt->bindValue(':schedule', null === $user->schedule()? null : Json::encode($user->schedule()->jsonSerialize()));

        $stmt->execute();
    }

    private function map($user): User
    {
        $rawSettings = null === $user['settings'] ? null : Json::decode($user['settings']);
        $rawClientData = '[]' === $user['client_data'] ? null : Json::decode($user['client_data']);
        $rawSchedule = null === $user['schedule'] ? null : Json::decode($user['schedule']);

        $settings = null;
        if (null !== $rawSettings) {
            $settings = UserSettings::from(
                ClockInPlatform::from($rawSettings['platform']),
                ClockInMode::from($rawSettings['mode']),
            );
        }

        $clientData = null;
        if (null !== $rawClientData) {
            $clientData = UserClientData::from($rawClientData);
        }

        $schedule = null;
        if (null !== $rawSchedule) {
            $schedule = UserSchedule::from(...array_map(
                static fn (array $clockIn) => ClockIn::from($clockIn['time'], $clockIn['randomnes']),
                $rawSchedule,
            ));
        }

        return User::create(
            Uuid::from($user['id']),
            $user['reference'],
            $user['username'],
            $settings,
            $clientData,
            $schedule,
        );
    }
}
