<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\User;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\User;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserRepository;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserEmail;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;

final class UserDbalRepository extends DbalRepository implements UserRepository
{
    private const TABLE_USER = 'users';

    public function byId(Uuid $id): ?User
    {
        // TODO: Implement byId() method.
    }

    public function byReference(UserReference $reference): ?User
    {
        $result = $this->connection
            ->createQueryBuilder()
            ->select('a.id, a.reference, a.email, a.password')
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
                INSERT INTO %s (id, reference, email, password) VALUES (
                    :id, :reference, :email, :password
                ) ON CONFLICT (id) DO UPDATE SET
                    id = :id, reference = :reference, email = :email, password = :password',
                self::TABLE_USER
            )
        );

        $stmt->bindValue(':id', $user->id()->value());
        $stmt->bindValue(':reference', $user->reference()->value());
        $stmt->bindValue(':email', $user->email()->value());
        $stmt->bindValue(':password', $user->password()->value());

        $stmt->execute();
    }

    private function map($result): User
    {
        return User::create(
            Uuid::v4(),
            UserReference::from($result['reference']),
            UserEmail::from($result['email']),
            UserPassword::from($result['password']),
            UserSettings::from(
                ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN),
                ClockInMode::from(ClockInMode::MODE_MANUAL),
                ClockInSchedule::from()
            )
        );
    }
}
