<?php

declare(strict_types=1);

namespace App\Contracts\Enums;

interface HasData
{
    public function data(): array;
}
