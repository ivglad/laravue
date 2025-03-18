<?php

declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Трейт для получения массива скрытых полей модели.
 */
trait GetHiddenFields
{
    /**
     * Массив скрытых полей модели.
     *
     * @return array
     */
    public static function getHiddenFields(): array
    {
        return (new static)->hidden;
    }
}
