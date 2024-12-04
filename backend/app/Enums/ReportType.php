<?php

declare(strict_types=1);

namespace App\Enums;

use App\Actions\Report\BalanceAction;
use App\Contracts\Enums\HasName;
use App\Traits\Enums\Objects;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum ReportType: string implements HasName
{
    use Names, Values, Options, Objects;

    case Balance = 'balance';

    public function name(): string
    {
        return match($this)
        {
            self::Balance => BalanceAction::class,
        };
    }

}
