<?php

namespace App\Models\Handbook;

use App\Http\Filters\PositionFilter;
use App\Models\Model;
use App\Traits\Models\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    public static string $filterModel = PositionFilter::class;

    protected $fillable = [
        'external_id',
        'name',
        'type',
        'is_tax',
        'is_transport',
    ];
}
