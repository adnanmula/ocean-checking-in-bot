<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\ClockIn;

use DemigrantSoft\ClockInBot\Domain\Model\ClockIn\ValueObject\ClockInDate;
use DemigrantSoft\ClockInBot\Model\ClockIn\ValueObject\ClockInRandomness;

final class ClockIn
{
    private \DateTimeInterface $checkInDate;
    private ClockInRandomness $randomness;

    private function __construct(ClockInDate $checkInDate, ClockInRandomness $randomness)
    {
        $this->checkInDate = $checkInDate;
        $this->randomness = $randomness;
    }

    public static function from(ClockInDate $checkInDate, ClockInRandomness $randomness): self
    {
        return new self($checkInDate, $randomness);
    }

    public function checkInDate(): \DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function randomness(): ClockInRandomness
    {
        return $this->randomness;
    }
}
