<?php

namespace App\Http\Filters\User;

use App\Http\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends Filter
{
    public array $sortFields = [
        'id',
        'created_at',
        'name',
        'surname',
        'patronymic',
        'job_title',
        'email',
        'username',
    ];

    public function all(?string $value = null): Builder
    {
        if ($value === null || trim($value) === '') {
            return $this->builder;
        }
        $value = explode(' ', $value);
        $this->builder->where(function ($q) use ($value) {
            $q->whereIn($this->table . '.email', $value)
                ->orWhereIn($this->table . '.phone', $value);
            foreach ($value as $v) {
                $q->orWhere($this->table . '.name', 'like', '%' . $v . '%')
                    ->orWhere($this->table . '.surname', 'like', '%' . $v . '%')
                    ->orWhere($this->table . '.patronymic', 'like', '%' . $v . '%')
                    ->orWhere($this->table . '.username', 'like', '%' . $v . '%')
                    ->orWhere($this->table . '.job_title', 'like', '%' . $v . '%');
            }
        });
        return $this->builder;
    }

    public function permissions(array $value = []): Builder
    {
        if ($value === []) {
            return $this->builder;
        }
        return $this->builder->where(function ($q) use ($value)  {
            $q->whereHas('permissions', function ($qP) use ($value) {
                $qP->whereIn('permissions.name', $value);
            })
            ->orWhereHas('roles', function ($qR) use ($value) {
                $qR->whereHas('permissions', function ($qP) use ($value) {
                    $qP->whereIn('permissions.name', $value);
                });
            });
        });
    }

    public function roles(array $value = []): Builder
    {
        if ($value === []) {
            return $this->builder;
        }
        return $this->builder->whereHas('roles', function ($q) use ($value) {
            $q->whereIn('roles.name', $value);
        });
    }

}
