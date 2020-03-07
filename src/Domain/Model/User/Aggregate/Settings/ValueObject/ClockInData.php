<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject;

use Pccomponentes\Ddd\Domain\Model\ValueObject\ValueObject;

final class ClockInData implements ValueObject
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function from(array ...$data): self
    {
        return new self(\array_merge(...$data));
    }

    public function __call(string $key, array $arguments): string
    {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        //throw algo
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
