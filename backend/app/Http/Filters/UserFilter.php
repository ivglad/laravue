<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends Filter
{
    public array $sortFields = [
        'id',
    ];

    public function all(string $value = null): Builder
    {
        return $this->builder->where(function ($query) use ($value) {
            $value = explode(' ', $value);
            $value = array_filter($value, fn($element) => !is_null($element) && $element !== '' && $element !== ' ');

            if (count($value) < 10) {
                $value = array_map(fn($element) => !empty(trim($element)) ? '%' . $element . '%' : null, $value);
                foreach ($value as $element) {
                    if (is_numeric(str_replace('%', '', $element))) {
                        $query->orWhere($this->table . '.phone', 'like', $element);
                    }
                    elseif (!preg_match('/[А-Яа-яЁё]/u', $element)) {
                        $query->orWhere($this->table . '.email', 'like', $element)
                            ->orWhere($this->table . '.username', 'like', $element);
                    } else {
                        $query->orWhere($this->table . '.name', 'like', $element)
                            ->orWhere($this->table . '.surname', 'like', $element)
                            ->orWhere($this->table . '.patronymic', 'like', $element)
                            ->orWhere($this->table . '.job', 'like', $element);
                    }

                }
            } else {
                $query->orWhereIn($this->table . '.name', $value)
                    ->orWhereIn($this->table . '.surname', $value)
                    ->orWhereIn($this->table . '.patronymic', $value)
                    ->orWhereIn($this->table . '.phone', $value)
                    ->orWhereIn($this->table . '.job', $value)
                    ->orWhereIn($this->table . '.email', $value)
                    ->orWhereIn($this->table . '.username', $value);
            }
        });
    }
}
