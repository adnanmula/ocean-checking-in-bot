<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\User;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInData;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\User;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserRepository;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;

final class UserDbalRepository extends DbalRepository implements UserRepository
{
    private const TABLE_USER = 'users';

    public function byId(UserId $id): ?User
    {
        $result = $this->connection
            ->createQueryBuilder()
            ->select('a.id, a.reference, a.username')
            ->from(self::TABLE_USER, 'a')
            ->where('a.reference = :reference')
            ->setParameter('id', $id->value())
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        if (false === $result) {
            return null;
        }

        return $this->map($result);
    }

    public function byReference(UserReference $reference): ?User
    {
        $result = $this->connection
            ->createQueryBuilder()
            ->select('a.id, a.reference, a.username')
            ->from(self::TABLE_USER, 'a')
            ->where('a.reference = :reference')
            ->setParameter('reference', $reference->value())
            ->setMaxResults(1)
            ->execute()
            ->fetch();

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
                self::TABLE_USER
            )
        );

        $stmt->bindValue(':id', $user->aggregateId()->value());
        $stmt->bindValue(':reference', $user->reference()->value());
        $stmt->bindValue(':username', $user->username()->value());

        $stmt->execute();
    }

    private function map($result): User
    {
        return User::create(
            UserId::from($result['id']),
            UserReference::from($result['reference']),
            UserUsername::from($result['username']),
            UserSettings::from(
                ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                ClockInMode::from(ClockInMode::MODE_MANUAL),
                ClockInSchedule::from(),
                ClockInData::from(),
            )
        );
    }
}
