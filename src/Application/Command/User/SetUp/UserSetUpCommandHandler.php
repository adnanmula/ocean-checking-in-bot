<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Domain\Model\User\Exception\PlatformNotSupportedException;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\Ocean\OceanUserData;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSettings;
use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserSetUpCommandHandler implements MessageHandlerInterface
{
    private UserFinderByReference $userFinder;
    private UserRepository $userRepository;

    public function __construct(UserFinderByReference $userFinder, UserRepository $userRepository)
    {
        $this->userFinder = $userFinder;
        $this->userRepository = $userRepository;
    }

    public function __invoke(UserSetUpCommand $command): void
    {
        if (false === $command->platform()->isOcean()) {
            throw new PlatformNotSupportedException();
        }

        $user = $this->userFinder->execute($command->reference());

        $user->updateSettings(UserSettings::from(
            $command->platform(),
            $user->settings()->mode(),
        ));

        $user->updateClientData(OceanUserData::from($command->parameters()));

        $this->userRepository->save($user);
    }
}
