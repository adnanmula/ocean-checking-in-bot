<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\CheckIn;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception\NotFoundException;

final class ClientNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('Check in client not found.');
    }
}
