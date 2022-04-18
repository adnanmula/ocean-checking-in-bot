<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\User;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserId;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use AdnanMula\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserDbalRepository extends DbalRepository implements UserRepository
{
    private const TABLE_USER = 'users';

    public function byId(Uuid $id): ?User
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
                self::TABLE_USER,
            ),
        );

        $stmt->bindValue(':id', $user->id()->value());
        $stmt->bindValue(':reference', $user->reference()->value());
        $stmt->bindValue(':username', $user->username()->value());

        $stmt->execute();
    }

    private function map($user): User
    {
        return User::create(
            UserId::from($user['id']),
            UserReference::from($user['reference']),
            UserUsername::from($user['username']),
        );
    }
}
