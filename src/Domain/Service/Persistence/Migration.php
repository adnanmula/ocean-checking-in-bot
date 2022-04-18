<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Service\Persistence;

interface Migration
{
    public function up(): void;
    public function down(): void;
}
