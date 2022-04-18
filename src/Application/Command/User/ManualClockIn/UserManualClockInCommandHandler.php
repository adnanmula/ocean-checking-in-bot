<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\ManualClockIn;

use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use AdnanMula\ClockInBot\Domain\Service\UserClientData\UserClientDataFinderByUserId;
use AdnanMula\ClockInBot\Domain\Service\UserSettings\UserSettingsFinderByUserId;
use AdnanMula\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserManualClockInCommandHandler implements MessageHandlerInterface
{
    private UserFinderByReference $userFinder;
    private ClientFactory $factory;

    public function __construct(
        UserFinderByReference $userFinder,
        UserSettingsFinderByUserId $settingsFinder,
        UserClientDataFinderByUserId $clientDataFinder,
        ClientFactory $factory
    ) {
        $this->userFinder = $userFinder;
        $this->settingsFinder = $settingsFinder;
        $this->clientDataFinder = $clientDataFinder;
        $this->factory = $factory;
    }

    public function __invoke(UserManualClockInCommand $command): void
    {
        $user = $this->userFinder->execute($command->reference());

        $client = $this->factory->build($user->settings()->platform(), $user->clientData());

        $client->clockIn();
    }
}
