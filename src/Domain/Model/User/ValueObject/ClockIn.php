<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

final class ClockIn implements \JsonSerializable
{
    private \DateTimeInterface $clockInDate;
    private ClockInRandomness $randomness;

    private function __construct(\DateTimeImmutable $clockInDate, ClockInRandomness $randomness)
    {
        $this->clockInDate = $clockInDate;
        $this->randomness = $randomness;
    }

    public static function from(\DateTimeImmutable $clockInDate, ClockInRandomness $randomness): self
    {
        return new self($clockInDate, $randomness);
    }

    public function clockInDate(): \DateTimeImmutable
    {
        return $this->clockInDate;
    }

    public function randomness(): ClockInRandomness
    {
        return $this->randomness;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'time' => $this->clockInDate,
            'randomness' => $this->randomness,
        ];
    }
}
