<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\SetUp;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinderByReference;
use DemigrantSoft\ClockInBot\Domain\Service\UserClientData\UserClientDataCreator;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsCreator;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsRemover;
use Doctrine\DBAL\Connection;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserSetUpCommandHandler
{
    private Connection $connection;
    private UserFinderByReference $userFinder;
    private UserSettingsRemover $settingsRemover;
    private UserSettingsCreator $settingsCreator;
    private UserClientDataCreator $dataCreator;

    public function __construct(
        Connection $connection,
        UserFinderByReference $userFinder,
        UserSettingsRemover $settingsRemover,
        UserSettingsCreator $settingsCreator,
        UserClientDataCreator $dataCreator
    ) {
        $this->connection = $connection;
        $this->userFinder = $userFinder;
        $this->settingsRemover = $settingsRemover;
        $this->settingsCreator = $settingsCreator;
        $this->dataCreator = $dataCreator;
    }

    public function __invoke(UserSetUpCommand $command): void
    {
        $user = $this->userFinder->execute($command->reference());

        $this->connection->beginTransaction();

        $this->settingsRemover->execute($user->aggregateId());

        $this->settingsCreator->execute(
            $user->aggregateId(),
            $command->platform(),
            ClockInMode::from(ClockInMode::MODE_MANUAL)
        );

        $this->dataCreator->execute(
            Uuid::from($user->aggregateId()->value()),
            $command->data()
        );

        $this->connection->commit();
    }
}
