<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Query\User\GetConfig;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIn;
use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use AdnanMula\ClockInBot\Domain\Service\UserClientData\UserClientDataFinderByUserId;
use AdnanMula\ClockInBot\Domain\Service\UserSchedule\UserScheduleFinderByUserId;
use AdnanMula\ClockInBot\Domain\Service\UserSettings\UserSettingsFinderByUserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetConfigQueryHandler implements MessageHandlerInterface
{
    private UserFinderByReference $userFinder;

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

        return [
            'config' => [
                'platform' => $user->settings()->platform(),
                'mode' => $user->settings()->mode(),
            ],
            'data' => $user->clientData()->all(),
            'schedule' => \array_map(
                static fn (ClockIn $clockIn) => ['randomness' => $clockIn->randomness(), 'time' => $clockIn->clockInDate()->format('H:i:s')],
                $user->schedule()->clockIns(),
            ),
        ];
    }
}
