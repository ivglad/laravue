<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filter
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The builder instance.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    protected string $table;

    /**
     * Initialize a new filter instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters on the builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        $this->table = $this->builder->getModel()->getTable();
        if ($this->request->filled('filter') && is_array($this->request->filter)) {
            foreach ($this->request->filter as $name => $value) {
                if (method_exists($this, $name)) {
                    call_user_func_array([$this, $name], array_filter([$value]));
                }
            }
        }
        if ($this->request->filled('sort') && is_array($this->request->sort)) {
            foreach ($this->request->sort as $value => $direction) {
                if (!in_array($direction, ['asc', 'desc'])) {
                    $direction = 'desc';
                }
                if ($value !== null && $value !== '') {
                    if (isset($this->sortFields) && in_array($value, $this->sortFields)) {
                        $this->orderBy($value, $direction);
                    }
                }
            }
        }

        return $this->builder;
    }

    public function orderBy(string $field, string $direction = 'asc'): void
    {
        if (str_contains($field, '.')) {
            [$relation, $field] = explode('.', $field);
            if (method_exists($this->builder->getModel(), $relation)) {
                $relationTable = (new ($this->builder->getModel()))->$relation()->getRelated()->getTable();
                $this->builder = $this->builder
                        ->selectRaw($this->table . '.*')
                    ->leftJoin(
                    $relationTable,
                    $this->table . '.' . $relation . '_id',
                    '=',
                    $relationTable . '.id',
                )->orderBy($relationTable . '.' . $field, $direction);
            }
        } else {
            $this->builder = $this->builder->orderBy($field, $direction);
        }
    }
}

