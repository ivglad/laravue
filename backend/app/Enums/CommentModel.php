<?php
declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Enums\HasName;
use App\Models\User;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;
use ArchTech\Enums\InvokableCases;

enum CommentModel: string implements HasName
{
    use Names, Values, Options, InvokableCases;

    case User = 'user';

    public function name(): string
    {
        return match($this)
        {
            self::User => User::class,
        };
    }

}
