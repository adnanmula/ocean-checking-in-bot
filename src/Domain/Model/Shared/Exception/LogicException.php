<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\Shared\Exception;

use Symfony\Component\HttpFoundation\Response;

abstract class LogicException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, Response::HTTP_CONFLICT);
    }
}