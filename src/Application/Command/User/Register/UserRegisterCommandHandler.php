<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\Register;

use DemigrantSoft\ClockInBot\Domain\Service\User\UserCreator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserRegisterCommandHandler implements MessageHandlerInterface
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