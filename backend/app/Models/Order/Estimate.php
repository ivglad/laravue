<?php

namespace App\Models\Order;

use App\Models\Handbook\Counterparty;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'counterparty_to_id',
        'counterparty_from_id',
        'order_id',
        'sort',
        'currency_code',
    ];

    public function counterparty_to(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function counterparty_from(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(EstimatePosition::class);
    }

    public function payment_periods(): HasMany
    {
        return $this->hasMany(PaymentPeriod::class);
    }
}
