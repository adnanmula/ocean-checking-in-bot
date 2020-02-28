<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\Shared\ValueObject;

class DateTimeValueObject extends \DateTimeImmutable implements ValueObject
{
    final public function jsonSerialize(): string
    {
        return $this->format(\DATE_ATOM);
    }

    final public static function from(string $str): self
    {
        return new static($str, new \DateTimeZone('UTC'));
    }

    final public static function fromTimestamp(int $timestamp): self
    {
        $dateTime = \DateTimeImmutable::createFromFormat('U', (string) $timestamp);
        return static::from($dateTime->format(\DATE_ATOM));
    }
}
