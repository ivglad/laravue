<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Трейт для фильтра через Request и специализированного класса, указанного в static::$filterModel.
 */
trait Filterable
{
    /**
     * Применение фильтров
     *
     * @param Builder $query
     * @param array $removeFilters Массив полей фильтра, которые не нужно применять
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $removeFilters = []): Builder
    {
        $request = resolve(Request::class);
        if (isset(self::$filterModel)) {
            $filter = new self::$filterModel($request);
            return $filter->apply($query, $removeFilters);
        } else {
            return $query;
        }
    }
}
