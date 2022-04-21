<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Query\User\GetHelp;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetHelpQueryHandler implements MessageHandlerInterface
{
    public function __construct(UserFinderByReference $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function __invoke(GetHelpQuery $query): string
    {
        if (\strtolower($query->platform()) !== \strtolower(ClockInPlatform::PLATFORM_OCEAN)) {
            return 'The clock in platform "' . $query->platform() . '" is not supported, currently the only one is "Ocean". '
                . PHP_EOL . 'Use /help Ocean to learn how to set it up.';
        }

//        TODO help command
        return 'Set up guide todo...';
    }
}
