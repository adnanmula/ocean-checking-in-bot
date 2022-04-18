<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\Client;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIns;

interface Client
{
    public function platform(): string;
    public function version(): string;

    public function clockIn(): void;
    public function clockIns(\DateTimeInterface $from, \DateTimeInterface $to): ClockIns;
}
