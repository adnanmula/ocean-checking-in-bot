<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInData;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;

final class UserSettings
{
    private ClockInMode $mode;
    private ClockInSchedule $schedule;
    private ClockInPlatform $client;
    private ClockInData $data;

    private function __construct(ClockInPlatform $client, ClockInMode $mode, ClockInSchedule $schedule, ClockInData $data)
    {
        $this->client = $client;
        $this->mode = $mode;
        $this->schedule = $schedule;
        $this->data = $data;
    }

    public static function from(ClockInPlatform $client, ClockInMode $mode, ClockInSchedule $schedule, ClockInData $data): self
    {
        return new self($client, $mode, $schedule, $data);
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

    public function data(): ClockInData
    {
        return $this->data;
    }
}
