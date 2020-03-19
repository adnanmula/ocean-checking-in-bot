<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\ManualClockIn;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinderByReference;
use DemigrantSoft\ClockInBot\Domain\Service\UserClientData\UserClientDataFinderByUserId;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsFinderByUserId;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserManualClockInCommandHandler implements MessageHandlerInterface
{
    private UserFinderByReference $userFinder;
    private UserSettingsFinderByUserId $settingsFinder;
    private UserClientDataFinderByUserId $clientDataFinder;
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
        $settings = $this->settingsFinder->execute(UserId::from($user->aggregateId()->value()));
        $data = $this->clientDataFinder->execute(UserId::from($user->aggregateId()->value()));

        $client = $this->factory->build($settings->platform(), $data);

        $client->clockIn();
    }
}
