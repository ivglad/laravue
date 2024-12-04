<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Enums\HasName;
use App\Models\Order\EstimatePosition;
use App\Models\Order\Order;
use App\Traits\Enums\Objects;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum FileModel: string implements HasName
{
    use Names, Values, Options, Objects;

    case Order = 'order';
    case EstimatePosition = 'estimate_position';

    public function name(): string
    {
        return match($this)
        {
            self::Order => Order::class,
            self::EstimatePosition => EstimatePosition::class,
        };
    }

}
