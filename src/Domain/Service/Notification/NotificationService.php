<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Service\Notification;

interface NotificationService
{
    public function notify(string $msg): void;
}
