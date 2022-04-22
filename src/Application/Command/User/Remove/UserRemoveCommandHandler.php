<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\Remove;

use AdnanMula\ClockInBot\Domain\Service\User\UserRemover;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserRemoveCommandHandler implements MessageHandlerInterface
{
    private UserRemover $remover;

    public function __construct(UserRemover $remover)
    {
        $this->remover = $remover;
    }

    public function __invoke(UserRemoveCommand $command): void
    {
        $this->remover->execute($command->reference());
    }
}
