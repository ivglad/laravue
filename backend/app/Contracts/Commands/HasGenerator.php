<?php

declare(strict_types=1);

namespace App\Contracts\Commands;

interface HasGenerator
{
    public function generateFile(array $data): string;
}
