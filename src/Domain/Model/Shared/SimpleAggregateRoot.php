<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\Shared;

use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;
use Pccomponentes\Ddd\Util\Message\AggregateMessage;

abstract class SimpleAggregateRoot
{
    private $aggregateId;
    private $events;

    final protected function __construct(Uuid $aggregateId)
    {
        $this->aggregateId = $aggregateId;
        $this->events = [];
    }

    final public function aggregateId(): Uuid
    {
        return $this->aggregateId;
    }

    final protected function recordThat(AggregateMessage $event): void
    {
        $this->events[] = $event;
    }

    final public function events(): array
    {
        return $this->events;
    }

    abstract public static function modelName(): string;
}
