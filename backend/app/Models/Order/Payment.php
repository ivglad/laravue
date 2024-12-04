<?php

namespace App\Models\Order;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_period_id',
        'payment_date',
        'amount',
        'currency_value',
        'is_income',
    ];


    protected $casts = [
        'is_income' => 'boolean',
    ];

    public function payment_period(): BelongsTo
    {
        return $this->belongsTo(PaymentPeriod::class);
    }
}
