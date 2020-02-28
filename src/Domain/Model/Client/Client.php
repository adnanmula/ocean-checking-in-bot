<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\Client;

interface Client
{
    public function platform(): string;
    public function version(): string;

    public function checkIn(): void;
    public function checkIns(\DateTimeInterface $from, \DateTimeInterface $to): array;
}
