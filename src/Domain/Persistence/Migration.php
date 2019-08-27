<?php declare(strict_types=1);

namespace App\Domain\Persistence;

interface Migration
{
    public function up(): void;
    public function down(): void;
}
