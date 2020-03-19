<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\UserClientData;

use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientData;
use DemigrantSoft\ClockInBot\Domain\Model\UserClientData\UserClientDataRepository;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Doctrine\Repository\DbalRepository;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserClientDataDbalRepository extends DbalRepository implements UserClientDataRepository
{
    private const TABLE_USER_CLIENT_DATA = 'user_client_data';

    public function byUserId(Uuid $id): ?UserClientData
    {
        $result = $this->connection
            ->createQueryBuilder()
            ->select('a.user_id, a.key, a.value')
            ->from(self::TABLE_USER_CLIENT_DATA, 'a')
            ->where('a.user_id = :user_id')
            ->setParameter('user_id', $id->value())
            ->execute()
            ->fetchAll();

        if (false === $result) {
            return null;
        }

        return $this->map($id, $result);
    }

    public function save(UserClientData $clientData): void
    {
        $this->connection->beginTransaction();

        foreach ($clientData->all() as $key => $value) {
            $stmt = $this->connection->prepare(
                \sprintf(
                    '
                INSERT INTO %s (user_id, key, value) VALUES (
                    :user_id, :key, :value
                ) ON CONFLICT (user_id, key) DO UPDATE SET
                    user_id = :user_id, key = :key, value = :value',
                    self::TABLE_USER_CLIENT_DATA,
                ),
            );

            $stmt->bindValue(':user_id', $clientData->userId()->value());
            $stmt->bindValue(':key', $key);
            $stmt->bindValue(':value', $value);

            $stmt->execute();
        }

        $this->connection->commit();
    }

    private function map(Uuid $id, array $data): UserClientData
    {
        $data = \array_map(static fn ($item) => [$item['key'] => $item['value']], $data);

        return UserClientData::from($id, \array_merge(...$data));
    }
}
