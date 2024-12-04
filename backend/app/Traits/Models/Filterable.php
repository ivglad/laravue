<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    /**
     * Apply all relevant filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query): Builder
    {
        $request = resolve(\Illuminate\Http\Request::class);
        if (isset(self::$filterModel)) {
            $filter = new self::$filterModel($request);
            return $filter->apply($query);
        }
        else {
            return $query;
        }
    }
}
