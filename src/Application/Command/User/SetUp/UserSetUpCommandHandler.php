<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\SetUp;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinderByReference;
use DemigrantSoft\ClockInBot\Domain\Service\UserClientData\UserClientDataCreator;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsCreator;
use Doctrine\DBAL\Connection;

final class UserSetUpCommandHandler
{
    private Connection $connection;
    private UserFinderByReference $userFinder;
    private UserSettingsCreator $settingsCreator;
    private UserClientDataCreator $dataCreator;

    public function __construct(Connection $connection, UserFinderByReference $userFinder, UserSettingsCreator $settingsCreator, UserClientDataCreator $dataCreator)
    {
        $this->connection = $connection;
        $this->userFinder = $userFinder;
        $this->settingsCreator = $settingsCreator;
        $this->dataCreator = $dataCreator;
    }

    public function __invoke(UserSetUpCommand $command): void
    {
        $user = $this->userFinder->execute($command->reference());

        $this->connection->beginTransaction();

        $this->settingsCreator->execute(
            UserId::from($user->aggregateId()->value()),
            $command->platform(),
            ClockInMode::from(ClockInMode::MODE_MANUAL)
        );

        $this->dataCreator->execute(
            UserId::from($user->aggregateId()->value()),
            $command->data()->all()
        );

        $this->connection->commit();
    }
}
