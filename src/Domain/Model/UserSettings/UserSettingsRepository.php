<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\UserSettings;

use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

interface UserSettingsRepository
{
    public function byUserId(Uuid $userId): ?UserSettings;
    public function save(UserSettings $settings): void;
    public function removeByUserId(Uuid $userId): void;
}
