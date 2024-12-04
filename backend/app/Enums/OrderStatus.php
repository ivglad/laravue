<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Enums\HasName;
use App\Traits\Enums\Objects;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum OrderStatus: string implements HasName
{
    use Names, Values, Options, Objects;

    case Draft = 'draft';
    case InWork = 'in_work';
    case Archive = 'archive';

    public function name(): string
    {
        return match($this)
        {
            self::Draft => 'Черновик',
            self::InWork => 'В работе',
            self::Archive => 'Архив',
        };
    }

}
