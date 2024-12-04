<?php

namespace App\Models\Handbook;

use App\Http\Filters\CounterpartyFilter;
use App\Models\Model;
use App\Models\Order\Order;
use App\Traits\Models\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counterparty extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    public static string $filterModel = CounterpartyFilter::class;

    protected $fillable = [
        'external_id',

        'inn',
        'okpo',
        'ogrn',

        'bik',
        'rs',
        'ks',
        'bank',

        'contact_name',
        'contact_job',
        'contact_phone',
        'contact_email',
        'contact_link',

        'name',
        'actual_address',
        'legal_address',
        'phone',
        'email',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
