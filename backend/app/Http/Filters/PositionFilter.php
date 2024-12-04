<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class PositionFilter extends Filter
{
    public array $sortFields = [
        'id',
        'created_at',
    ];

    public function name(string $value = null): Builder
    {
        return $this->builder
            ->where(function ($q) use ($value) {
                $values = explode(' ', $value);
                foreach ($values as $item) {
                    $q->orWhere($this->table . '.name', 'like', '%' . $item . '%');
                }
            });
    }

}
