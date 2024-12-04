<?php

namespace App\Models\Order;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_position_id',
        'estimate_id',
        'payment_date',
        'percent',
        'sum',
        'is_income',
        'currency_code',
    ];

    protected $casts = [
        'is_income' => 'boolean',
    ];

    public function estimate_position(): BelongsTo
    {
        return $this->belongsTo(EstimatePosition::class);
    }

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
