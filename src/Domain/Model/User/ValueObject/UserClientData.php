<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User\ValueObject;

use Symfony\Component\HttpFoundation\Response;

abstract class UserClientData implements \JsonSerializable
{
    private array $data;

    protected function __construct(array $data)
    {
        self::assert($data);

        $this->data = $data;
    }

    abstract static function from(array $data): self;

    final public function all(): array
    {
        return $this->data;
    }

    final public function __call(string $key, array $arguments): ?string
    {
        $key = \lcfirst(\substr($key, 3, \strlen($key)));

        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }

    final public function jsonSerialize(): array
    {
        return $this->all();
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
