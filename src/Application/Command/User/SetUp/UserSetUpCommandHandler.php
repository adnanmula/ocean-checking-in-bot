<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInMode;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;
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
        $user = $this->userFinder->execute($command->reference());

        $user->updateSettings(UserSettings::from(
            $command->platform(),
            ClockInMode::from(ClockInMode::MODE_MANUAL),
        ));

        $user->updateClientData(UserClientData::from($command->data()));

        $this->userRepository->save($user);
    }
}
