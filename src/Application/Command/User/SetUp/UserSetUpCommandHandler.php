<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
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

        if ($command->key() === 'platform') {
            $user->updateSettings(UserSettings::from(
                ClockInPlatform::from($command->value()),
                $user->settings()->mode(),
            ));
        }

        $dataKeys = ['lat', 'lon', 'baseurl', 'user', 'pass'];

        if (\in_array($command->key(), $dataKeys)) {
            $currentData = $user->clientData()->all();

            $user->updateClientData(UserClientData::from(
                \array_merge([$command->key() => $command->value()], $currentData)),
            );
        }

        $this->userRepository->save($user);
    }
}
