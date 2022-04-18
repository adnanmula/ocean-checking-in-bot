<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\Persistence;

interface Fixture
{
    public function load(): void;
    public function isLoaded(): bool;
    public function dependants(): array;
}
