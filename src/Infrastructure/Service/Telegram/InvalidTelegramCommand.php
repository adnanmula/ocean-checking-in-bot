<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\Telegram;

final class InvalidTelegramCommand extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid Telegram command');
    }
}
