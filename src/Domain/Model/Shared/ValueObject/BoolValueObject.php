<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\Shared\ValueObject;

abstract class BoolValueObject implements ValueObject
{
    private $value;

    protected function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function isTrue(): bool
    {
        return true === $this->value;
    }

    public function isFalse(): bool
    {
        return false === $this->value;
    }

    final public function jsonSerialize(): bool
    {
        return $this->value;
    }

    public static function from(bool $value)
    {
        return new static($value);
    }

    public static function true()
    {
        return new static(true);
    }

    public static function false()
    {
        return new static(false);
    }
}
