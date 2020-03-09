<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\Register;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserCreator;
use DemigrantSoft\ClockInBot\Domain\Service\UserSettings\UserSettingsCreator;

final class UserRegisterCommandHandler
{
    private UserCreator $creator;
    private UserSettingsCreator $settingsCreator;

    public function __construct(UserCreator $creator, UserSettingsCreator $settingsCreator)
    {
        $this->creator = $creator;
        $this->settingsCreator = $settingsCreator;
    }

    public function __invoke(UserRegisterCommand $command): void
    {
        $this->creator->execute(
            UserId::v4(),
            $command->reference(),
            $command->username(),
        );
    }
}
