<?php

namespace App\Enums;

use App\Contracts\Enums\HasName;
use App\Traits\Enums\Objects;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum CurrencyCode: string implements HasName
{
    use Names, Values, Options, Objects;

    case RUB = 'RUB';
    case CNY = 'CNY';
    case USD = 'USD';
    case EUR = 'EUR';


    public function name(): string
    {
        return match($this)
        {
            self::RUB => 'RUB',
            self::CNY => 'CNY',
            self::USD => 'USD',
            self::EUR => 'EUR',
        };
    }
}
