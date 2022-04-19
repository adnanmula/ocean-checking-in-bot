<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

use Symfony\Component\HttpFoundation\Response;

final class UserClientData
{
    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function from(array $data): self
    {
        self::assert($data);

        return new self($data);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function __call(string $key, array $arguments): ?string
    {
        $key = \lcfirst(\substr($key, 3, \strlen($key)));

        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }

    private static function assert(array $data): void
    {
        foreach ($data as $key => $datum) {
            if (\is_numeric($key) || false === \is_string($datum)) {
                throw new \InvalidArgumentException('Invalid client data.', Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
