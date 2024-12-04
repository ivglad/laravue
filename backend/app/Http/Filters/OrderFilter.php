<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OrderFilter extends Filter
{
    public array $sortFields = [
        'id',
        'created_at',
        'status',
        'counterparty.name',
        'agreement.id',
        'user.surname',
    ];

    public function all(string $value = null): Builder
    {
        return $this->builder
            ->where(function ($q) use ($value) {
                $values = explode(' ', $value);
                foreach ($values as $item) {
                    $q->orWhereHas('counterparty', function ($qSub) use ($item) {
                        $qSub->where('name', 'like', '%' . $item . '%');
                    })->orWhereHas('agreement', function ($qSub) use ($item) {
                        $preparedValue = str_replace('.', '-', $item);
                        $preparedValue = str_replace(',', '-', $preparedValue);
                        $qSub->where('number', 'like', '%' . $preparedValue . '%');
                    })->orWhere(function ($qSub) use ($item) {
                        $preparedValue = str_replace('-', '.', $item);
                        $preparedValue = str_replace(',', '.', $preparedValue);
                        $qSub->where($this->table . '.number', 'like', '%' . $preparedValue . '%');
                    });
                }
            });
    }

    public function status(string $value = null): Builder
    {
        return $this->builder->where($this->table . '.status', $value);
    }

    public function counterparty(array $value = null): Builder
    {
        return $this->builder->whereIn($this->table . '.counterparty_id', $value);
    }

    public function agreement(array $value = null): Builder
    {
        return $this->builder->whereIn($this->table . '.agreement_id', $value);
    }

    public function user(array $value = null): Builder
    {
        return $this->builder->whereIn($this->table . '.user_id', $value);
    }

    public function created(array $value = null): Builder
    {
        if (isset($value['from'])) {
            $value['from'] = Carbon::create($value['from'])->format('Y-m-d 00:00:00');
            $this->builder->where($this->table . '.created_at', '>=', $value['from']);
        }
        if (isset($value['to'])) {
            $value['to'] = Carbon::create($value['to'])->format('Y-m-d 23:59:48');
            $this->builder->where($this->table . '.created_at', '<=', $value['to']);
        }

        return $this->builder;
    }
}
