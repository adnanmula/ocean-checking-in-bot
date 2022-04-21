<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\Register;

use AdnanMula\ClockInBot\Domain\Service\User\UserCreator;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
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
            Uuid::v4(),
            $command->reference(),
            $command->username(),
        );
    }
}
