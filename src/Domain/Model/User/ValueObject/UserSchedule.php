<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

final class UserSchedule
{
    /** @var array<ClockIn> */
    private array $clockIns;

    private function __construct(array $clockIns)
    {
        $this->clockIns = $clockIns;
    }

    public function clockIns(): array
    {
        return $this->clockIns;
    }

    public static function from(ClockIn ...$checkIn)
    {
        return new self($checkIn);
    }
}
