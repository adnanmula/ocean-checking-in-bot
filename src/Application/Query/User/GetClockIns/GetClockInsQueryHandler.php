<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Query\User\GetClockIns;

use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\ValueObject\ClockIns;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinderByReference;
use DemigrantSoft\ClockInBot\Domain\Service\UserClientData\UserClientDataFinderByUserId;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsFinderByUserId;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetClockInsQueryHandler implements MessageHandlerInterface
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

    public function __invoke(GetClockInsQuery $query): ClockIns
    {
        $user = $this->userFinder->execute($query->userReference());

        $settings = $this->settingsFinder->execute($user->aggregateId());
        $data = $this->clientDataFinder->execute($user->aggregateId());

        $client = $this->factory->build($settings->platform(), $data);

        $from = null !== $query->from()
            ? $query->from()
            : new \DateTimeImmutable();

        $to = null !== $query->to()
            ? $query->to()
            : $from;

        return $client->clockIns($from, $to);
    }
}
