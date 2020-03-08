<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\UserClientData;

use Pccomponentes\Ddd\Domain\Model\ValueObject\ValueObject;

final class UserClientData
{
    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function from(array ...$data): self
    {
        return new self(\array_merge(...$data));
    }

    public function all(): array
    {
        return $this->data;
    }

    public function __call(string $key, array $arguments): ?string
    {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }
}
