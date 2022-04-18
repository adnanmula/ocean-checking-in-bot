<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInMode;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserId;
use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use AdnanMula\ClockInBot\Domain\Service\UserClientData\UserClientDataCreator;
use AdnanMula\ClockInBot\Domain\Service\UserSettings\UserSettingsCreator;
use AdnanMula\ClockInBot\Domain\Service\UserSettings\UserSettingsRemoverByUserId;
use Doctrine\DBAL\Connection;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserSetUpCommandHandler implements MessageHandlerInterface
{
    private Connection $connection;
    private UserFinderByReference $userFinder;
    private UserSettingsRemoverByUserId $settingsRemover;
    private UserSettingsCreator $settingsCreator;
    private UserClientDataCreator $dataCreator;

    public function __construct(
        Connection $connection,
        UserFinderByReference $userFinder,
        UserSettingsRemoverByUserId $settingsRemover,
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

        $this->settingsRemover->execute($user->id());

        $this->settingsCreator->execute(
            $user->id(),
            $command->platform(),
            ClockInMode::from(ClockInMode::MODE_MANUAL),
        );

        $this->dataCreator->execute(
            Uuid::from($user->id()->value()),
            $command->data(),
        );

        $this->connection->commit();
    }
}
