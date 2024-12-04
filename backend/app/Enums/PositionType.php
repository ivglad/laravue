<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enums\Objects;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PositionType: string
{
    use Names, Values, Options, Objects;

    case Product = 'product';
    case Service = 'service';
}
