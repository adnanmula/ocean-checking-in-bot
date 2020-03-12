<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Query\User\GetConfig;

use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinderByReference;
use DemigrantSoft\ClockInBot\Domain\Service\UserClientData\UserClientDataFinder;
use DemigrantSoft\ClockInBot\Domain\Service\UserSchedule\UserScheduleFinderByUserId;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsFinder;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;

final class GetConfigQueryHandler
{
    private UserFinderByReference $userFinder;
    private UserSettingsFinder $settingsFinder;
    private UserClientDataFinder $clientDataFinder;
    private UserScheduleFinderByUserId $scheduleFinder;
    private ClientFactory $factory;

    public function __construct(
        UserFinderByReference $userFinder,
        UserSettingsFinder $settingsFinder,
        UserClientDataFinder $clientDataFinder,
        UserScheduleFinderByUserId $scheduleFinder,
        ClientFactory $factory
    ) {
        $this->userFinder = $userFinder;
        $this->settingsFinder = $settingsFinder;
        $this->clientDataFinder = $clientDataFinder;
        $this->scheduleFinder = $scheduleFinder;
        $this->factory = $factory;
    }

    public function __invoke(GetConfigQuery $query): array
    {
        $user = $this->userFinder->execute($query->userReference());

        $settings = $this->settingsFinder->execute($user->aggregateId());
        $data = $this->clientDataFinder->execute($user->aggregateId());
        $schedule = $this->scheduleFinder->execute($user->aggregateId());

        return [
            'config' => $settings->jsonSerialize(),
            'data' => $data->jsonSerialize(),
            'schedule' => $schedule->jsonSerialize(),
        ];
    }
}
