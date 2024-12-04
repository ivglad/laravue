<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CounterpartyFilter extends Filter
{
    public array $sortFields = [
        'id',
        'created_at',
    ];

    public function all(string $value = null): Builder
    {
        return $this->builder
            ->where(function ($q) use ($value) {
                $values = explode(' ', $value);
                foreach ($values as $item) {
                    $q->orWhere($this->table . '.inn', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.okpo', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.ogrn', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.bik', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.rs', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.ks', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.bank', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.contact_name', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.contact_phone', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.contact_email', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.name', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.actual_address', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.legal_address', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.phone', 'like', '%' . $item . '%')
                        ->orWhere($this->table . '.email', 'like', '%' . $item . '%');
                }
            });
    }

}
