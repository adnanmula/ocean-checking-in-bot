<?php declare(strict_types=1);

namespace App\Domain\Notification;

interface NotificationService
{
    public function notify(string $msg): void;
}
