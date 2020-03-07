<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\ClockIn;

use DemigrantSoft\ClockInBot\Domain\Model\ClockIn\ValueObject\ClockInDate;
use DemigrantSoft\ClockInBot\Domain\Model\ClockIn\ValueObject\ClockInRandomness;

final class ClockIn
{
    private \DateTimeInterface $clockInDate;
    private ClockInRandomness $randomness;

    private function __construct(ClockInDate $clockInDate, ?ClockInRandomness $randomness)
    {
        $this->clockInDate = $clockInDate;
        $this->randomness = $randomness;
    }

    public static function from(ClockInDate $clockInDate, ?ClockInRandomness $randomness): self
    {
        return new self($clockInDate, $randomness);
    }

    public function clockInDate(): \DateTimeInterface
    {
        return $this->clockInDate;
    }

    public function randomness(): ?ClockInRandomness
    {
        return $this->randomness;
    }
}
