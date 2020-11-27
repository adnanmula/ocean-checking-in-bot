<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Tests\Mock\Domain\Model\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class UserSettingsMockProvider
{
    private Uuid $userId;
    private ClockInPlatform $platform;
    private ClockInMode $mode;

    public function __construct()
    {
        $this->userId = Uuid::v4();
        $this->platform = ClockInPlatform::from(ClockInPlatform::PLATFORM_OCEAN);
        $this->mode = ClockInMode::from(ClockInMode::MODE_MANUAL);
    }

    public function build(): UserSettings
    {
        return UserSettings::create($this->userId, $this->platform, $this->mode);
    }

    public function setUserId(Uuid $id): self
    {
        $this->userId = $id;

        return $this;
    }

    public function setPlatform(ClockInPlatform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function setMode(ClockInMode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }
}
