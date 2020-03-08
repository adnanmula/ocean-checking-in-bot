<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;

final class UserSettings
{
    private UserId $userId;
    private ClockInPlatform $platform;
    private ClockInMode $mode;

    private function __construct(UserId $userId, ClockInPlatform $platform, ClockInMode $mode)
    {
        $this->userId = $userId;
        $this->platform = $platform;
        $this->mode = $mode;
    }

    public static function create(UserId $userId, ClockInPlatform $platform, ClockInMode $mode): self
    {
        return new self($userId, $platform, $mode);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function platform(): ClockInPlatform
    {
        return $this->platform;
    }

    public function mode(): ClockInMode
    {
        return $this->mode;
    }
}
