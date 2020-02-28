<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\CheckIn;

final class ClientData
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
}
