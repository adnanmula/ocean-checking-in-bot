<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class ClientNotFoundException extends NotFoundException
{
    public function __construct(string $platform)
    {
        parent::__construct('Clock in platform ' . $platform . ' is not supported yet.');
    }
}
