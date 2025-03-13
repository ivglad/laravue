<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Трейт для фильтра активности по полю is_active.
 */
trait HasActive
{
    /**
     * Применение фильтра is_active = true.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.is_active', true);
    }
}
