<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

final class UserSettings implements \JsonSerializable
{
    private ClockInPlatform $platform;
    private ClockInMode $mode;

    private function __construct(ClockInPlatform $platform, ClockInMode $mode)
    {
        $this->platform = $platform;
        $this->mode = $mode;
    }

    public static function from(ClockInPlatform $platform, ClockInMode $mode): self
    {
        return new self($platform, $mode);
    }

    public function platform(): ClockInPlatform
    {
        return $this->platform;
    }

    public function mode(): ClockInMode
    {
        return $this->mode;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'platform' => $this->platform->value(),
            'mode' => $this->mode->value(),
        ];
    }
}
