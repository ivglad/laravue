<?php

namespace App\Models\Order;

use App\Models\Agreement\Agreement;
use App\Models\Handbook\Position;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EstimatePosition extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'estimate_id',
        'position_id',
        'quantity',
        'currency_code',
        'currency_value',
        'currency_increase',
        'weight',
        'price',
        'sum',
        'sum_tax',
        'agreement_id',
        'is_income',
        'meta',
        'foreign_tax',
    ];

    protected $casts = [
        'is_income' => 'boolean',
        'meta' => 'array',
    ];

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    public function payment_periods(): HasMany
    {
        return $this->hasMany(PaymentPeriod::class);
    }
}
