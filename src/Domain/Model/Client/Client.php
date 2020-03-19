<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\Client;

use DemigrantSoft\ClockInBot\Domain\Model\UserSchedule\ValueObject\ClockIns;

interface Client
{
    public function platform(): string;
    public function version(): string;

    public function clockIn(): void;
    public function clockIns(\DateTimeInterface $from, \DateTimeInterface $to): ClockIns;
}
