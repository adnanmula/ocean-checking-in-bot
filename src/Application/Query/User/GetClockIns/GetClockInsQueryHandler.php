<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Query\User\GetClockIns;

use DemigrantSoft\ClockInBot\Domain\Model\ClockIn\ClockIns;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserFinder;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;

final class GetClockInsQueryHandler
{
    private UserFinder $finder;
    private ClientFactory $factory;

    public function __construct(UserFinder $finder, ClientFactory $factory)
    {
        $this->finder = $finder;
        $this->factory = $factory;
    }

    public function __invoke(GetClockInsQuery $query): ClockIns
    {
        $user = $this->finder->execute($query->userId());

        $client = $this->factory->build($user->settings()->client(), $user->settings()->data());

        $from = null !== $query->from()
            ? $query->from()
            : new \DateTimeImmutable();

        $to = null !== $query->to()
            ? $query->to()
            : $from;

        return $client->clockIns($from, $to);
    }
}
