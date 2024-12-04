<?php

declare(strict_types=1);

namespace App\Traits\Enums;

trait Objects
{
    /** Get an array of objects. */
    public static function objects(): array
    {
        return array_map(fn($case) => ['id' => $case->value, 'name' => $case->name()], static::cases());
    }
}
