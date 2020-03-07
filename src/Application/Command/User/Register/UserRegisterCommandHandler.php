<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\Register;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInData;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserCreator;

final class UserRegisterCommandHandler
{
    private UserCreator $creator;

    public function __construct(UserCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(UserRegisterCommand $command): void
    {
        $this->creator->execute(
            UserId::v4(),
            $command->reference(),
            $command->username(),
            UserSettings::from(
                $command->platform(),
                ClockInMode::from(ClockInMode::MODE_MANUAL),
                ClockInSchedule::from(),
                ClockInData::from(),
            )
        );
    }
}
