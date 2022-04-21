<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Query\User\GetClockIns;

use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserSetupPendingException;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIns;
use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use AdnanMula\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetClockInsQueryHandler implements MessageHandlerInterface
{
    private UserFinderByReference $userFinder;
    private ClientFactory $factory;

    public function __construct(UserFinderByReference $userFinder, ClientFactory $factory)
    {
        $this->userFinder = $userFinder;
        $this->factory = $factory;
    }

    public function __invoke(GetClockInsQuery $query): ClockIns
    {
        $user = $this->userFinder->execute($query->userReference());

        if (null === $user->settings() || null === $user->clientData()) {
            throw new UserSetupPendingException();
        }

        $client = $this->factory->build($user->settings()->platform(), $user->clientData());

        $from = null !== $query->from() ? $query->from() : new \DateTimeImmutable();
        $to = null !== $query->to() ? $query->to() : $from;

        return $client->clockIns($from, $to);
    }
}
