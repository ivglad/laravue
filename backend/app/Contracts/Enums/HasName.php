<?php

declare(strict_types=1);

namespace App\Contracts\Enums;

interface HasName
{
    public function name(): string;
}
