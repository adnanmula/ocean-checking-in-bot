<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\ClockIn;

use AdnanMula\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class ClientNotFoundException extends NotFoundException
{
    public function __construct(string $platform)
    {
        parent::__construct('Clock in platform ' . $platform . ' is not supported yet.');
    }
}
