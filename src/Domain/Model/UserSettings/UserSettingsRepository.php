<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserSettings;

use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;

interface UserSettingsRepository
{
    public function byUserId(UserId $userId): ?UserSettings;
    public function save(UserSettings $settings): void;
}
