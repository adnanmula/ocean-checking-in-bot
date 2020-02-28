<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\CheckIn;

use DemigrantSoft\ClockInBot\Domain\Model\CheckIn\ValueObject\CheckInDate;
use DemigrantSoft\ClockInBot\Model\CheckIn\ValueObject\CheckInRandomness;

final class CheckIn
{
    private \DateTimeInterface $checkInDate;
    private CheckInRandomness $randomness;

    private function __construct(CheckInDate $checkInDate, CheckInRandomness $randomness)
    {
        $this->checkInDate = $checkInDate;
        $this->randomness = $randomness;
    }

    public static function from(CheckInDate $checkInDate, CheckInRandomness $randomness): self
    {
        return new self($checkInDate, $randomness);
    }

    public function checkInDate(): \DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function randomness(): CheckInRandomness
    {
        return $this->randomness;
    }
}
