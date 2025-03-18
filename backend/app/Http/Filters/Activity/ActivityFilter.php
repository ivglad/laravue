<?php

namespace App\Http\Filters\Activity;

use App\Http\Filters\Filter;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ActivityFilter extends Filter
{
    public array $sortFields = [
        'id',
        'created_at',
    ];

    public function all(?string $value = null): Builder
    {
        if ($value === null || trim($value) === '') {
            return $this->builder;
        }
        $value = explode(' ', $value);

        $this->builder->where(function ($q) use ($value) {
            $q->where($this->table . '.id', '<', 0);
            foreach ($value as $v) {
                $q->orWhere($this->table . '.description', 'like', '%' . $v . '%');
                $subjectType = searchFirstKeyInMultidimensionalArray(mb_ucfirst(strtolower($v)), Activity::SUBJECTS);
                if ($subjectType) {
                    $q->orWhere($this->table . '.subject_type', $subjectType);
                }
                $event = array_search(strtolower($v), Activity::EVENT_TRANSLATIONS);
                if ($event) {
                    $q->orWhere($this->table . '.event', $event);
                }

            }
        });
        return $this->builder;
    }

    public function created_at(array $value = []): Builder
    {
        if ($value === [] || !isset($value['from']) || !isset($value['to'])) {
            return $this->builder;
        }
        $from = Carbon::parse(date('d.m.Y 00:00:00', strtotime($value['from'])));
        $to = Carbon::parse(date('d.m.Y 23:59:59', strtotime($value['to'])));
        return $this->builder
            ->whereDate($this->table . '.created_at', '>=', $from)
            ->whereDate($this->table . '.created_at', '<=', $to);
    }

    public function events(array $value = []): Builder
    {
        if ($value === []) {
            return $this->builder;
        }
        return $this->builder->whereIn($this->table . '.event', $value);
    }


    public function types(array $value = []): Builder
    {
        if ($value === []) {
            return $this->builder;
        }
        $subjectTypes = [];
        foreach ($value as $type) {
            $subjectTypes[] = searchFirstKeyInMultidimensionalArray($type, Activity::SUBJECTS) ?? $type;
        }

        return $this->builder->whereIn($this->table . '.subject_type', $subjectTypes);
    }

    public function changed_by(array $value = []): Builder
    {
        if ($value === []) {
            return $this->builder;
        }
        return $this->builder->whereIn($this->table . '.causer_id', $value);
    }

    public function subject_id(?string $value = null): Builder
    {
        if ($value === null) {
            return $this->builder;
        }
        return $this->builder->where($this->table . '.subject_id', $value);
    }
}
