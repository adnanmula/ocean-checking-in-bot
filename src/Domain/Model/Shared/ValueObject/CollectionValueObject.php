<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\Shared\ValueObject;

use PcComponentes\Ddd\Domain\Model\ValueObject\ValueObject;

class CollectionValueObject implements \Iterator, \Countable, ValueObject
{
    private array $items;

    final protected function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function from(array $items): static
    {
        return new static($items);
    }

    public function current(): mixed
    {
        return \current($this->items);
    }

    public function next(): void
    {
        \next($this->items);
    }

    public function key(): int|string|null
    {
        return \key($this->items);
    }

    public function valid(): bool
    {
        return \array_key_exists($this->key(), $this->items);
    }

    public function rewind(): void
    {
        \reset($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function walk(callable $func): void
    {
        \array_walk($this->items, $func);
    }

    public function filter(callable $func): static
    {
        return new static(\array_values(\array_filter($this->items, $func)));
    }

    public function map(callable $func): CollectionValueObject
    {
        return new static(\array_map($func, $this->items));
    }

    public function reduce(callable $func, $initial)
    {
        return \array_reduce($this->items, $func, $initial);
    }

    public function sort(callable $func): static
    {
        $items = $this->items;
        \usort($items, $func);

        return new static($items);
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function first(): mixed
    {
        return $this->items[array_key_first($this->items)] ?? null;
    }

    public function equalTo(CollectionValueObject $other): bool
    {
        return static::class === \get_class($other) && $this->items == $other->items;
    }

    final public function jsonSerialize(): array
    {
        return $this->items;
    }

    protected function addItem($item): self
    {
        $items = $this->items;
        $items[] = $item;

        return new static($items);
    }

    protected function removeItem($item): self
    {
        return $this->filter(
            static fn ($current) => $current !== $item,
        );
    }
}
