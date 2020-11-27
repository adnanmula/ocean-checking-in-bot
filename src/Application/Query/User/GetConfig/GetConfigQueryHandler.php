<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Query\User\GetConfig;

use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinderByReference;
use DemigrantSoft\ClockInBot\Domain\Service\UserClientData\UserClientDataFinderByUserId;
use DemigrantSoft\ClockInBot\Domain\Service\UserSchedule\UserScheduleFinderByUserId;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsFinderByUserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetConfigQueryHandler implements MessageHandlerInterface
{
    private UserFinderByReference $userFinder;
    private UserSettingsFinderByUserId $settingsFinder;
    private UserClientDataFinderByUserId $clientDataFinder;
    private UserScheduleFinderByUserId $scheduleFinder;

    public function __construct(
        UserFinderByReference $userFinder,
        UserSettingsFinderByUserId $settingsFinder,
        UserClientDataFinderByUserId $clientDataFinder,
        UserScheduleFinderByUserId $scheduleFinder
    ) {
        $this->userFinder = $userFinder;
        $this->settingsFinder = $settingsFinder;
        $this->clientDataFinder = $clientDataFinder;
        $this->scheduleFinder = $scheduleFinder;
    }

    public function __invoke(GetConfigQuery $query): array
    {
        $user = $this->userFinder->execute($query->userReference());

        $settings = $this->settingsFinder->execute($user->id());
        $data = $this->clientDataFinder->execute($user->id());
        $schedule = $this->scheduleFinder->execute($user->id());

        return [
            'config' => $settings->jsonSerialize(),
            'data' => $data->jsonSerialize(),
            'schedule' => $schedule->jsonSerialize(),
        ];
    }
}
