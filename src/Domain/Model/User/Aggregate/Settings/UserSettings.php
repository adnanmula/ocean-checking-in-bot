<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\User\Aggregate\Settings;

use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\CheckInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\CheckInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\UserClient;

final class UserSettings
{
    private CheckInMode $mode;
    private CheckInSchedule $schedule;
    private UserClient $client;

    private function __construct(UserClient $client, CheckInMode $mode, CheckInSchedule $schedule)
    {
        $this->client = $client;
        $this->mode = $mode;
        $this->schedule = $schedule;
    }

    public static function from(UserClient $client, CheckInMode $mode, CheckInSchedule $schedule): self
    {
        return new self($client, $mode, $schedule);
    }

    public function client(): UserClient
    {
        return $this->client;
    }

    public function mode(): CheckInMode
    {
        return $this->mode;
    }

    public function schedule(): CheckInSchedule
    {
        return $this->schedule;
    }
}
