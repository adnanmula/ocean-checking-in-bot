<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\Shared\Exception;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}
