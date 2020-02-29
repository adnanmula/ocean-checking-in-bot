<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;

final class UserSettings
{
    private ClockInMode $mode;
    private ClockInSchedule $schedule;
    private ClockInPlatform $client;

    private function __construct(ClockInPlatform $client, ClockInMode $mode, ClockInSchedule $schedule)
    {
        $this->client = $client;
        $this->mode = $mode;
        $this->schedule = $schedule;
    }

    public static function from(ClockInPlatform $client, ClockInMode $mode, ClockInSchedule $schedule): self
    {
        return new self($client, $mode, $schedule);
    }

    public function client(): ClockInPlatform
    {
        return $this->client;
    }

    public function mode(): ClockInMode
    {
        return $this->mode;
    }

    public function schedule(): ClockInSchedule
    {
        return $this->schedule;
    }
}
