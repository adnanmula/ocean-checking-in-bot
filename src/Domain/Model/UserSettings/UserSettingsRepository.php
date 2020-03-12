<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSettings;

use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserSettingsRepository
{
    public function byUserId(Uuid $userId): ?UserSettings;
    public function save(UserSettings $settings): void;
}
