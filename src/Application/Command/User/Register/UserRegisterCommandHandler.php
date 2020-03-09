<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\Register;

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
            $command->id(),
            $command->reference(),
            $command->username(),
        );
    }
}
