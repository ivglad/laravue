<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    /**
     * Экземпляр Request
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Экземпляр Builder
     *
     * @var Builder
     */
    protected Builder $builder;

    /**
     * Наименование таблицы запроса для избежания ошибок фильтрации и сортировок по нескольким таблицам
     *
     * @var string
     */
    protected string $table;

    /**
     * Инициализирует новый фильтр
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Применяет фильтры к Builder
     *
     * @param Builder $builder
     * @param array $removeFilters массив имен фильтров, которые не нужно применять
     * @return Builder
     */
    public function apply(Builder $builder, array $removeFilters = []): Builder
    {
        $this->builder = $builder;
        $this->table = $this->builder->getModel()->getTable();
        if ($this->request->filled('filter') && is_array($this->request->filter)) {
            foreach ($this->request->filter as $name => $value) {
                if (method_exists($this, $name)) {
                    if (empty($removeFilters) || !in_array($name, $removeFilters)) {
                        call_user_func_array([$this, $name], array_filter([$value]));
                    }
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

    /**
     * Применяет сортировку к Builder
     *
     * @param string $field
     * @param string $direction
     * @return void
     */
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

