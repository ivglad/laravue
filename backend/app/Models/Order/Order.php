<?php

namespace App\Models\Order;

use App\Http\Filters\OrderFilter;
use App\Models\Agreement\Agreement;
use App\Models\Comment\Comment;
use App\Models\Handbook\Counterparty;
use App\Models\Model;
use App\Models\User;
use App\Traits\Models\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Filterable;

    public static string $filterModel = OrderFilter::class;

    protected $fillable = [
        'number',
        'counterparty_id',
        'agreement_id',
        'status',
        'user_id',
    ];

    public const RELATIONS = [
        'comments',
        'user',
        'media',
        'counterparty',
        'agreement',
        'estimates',
        'estimates.counterparty_to',
        'estimates.counterparty_from',
        'estimates.payment_periods',
        'estimates.positions',
        'estimates.positions.media',
        'estimates.positions.position',
        'estimates.positions.agreement',
        'estimates.positions.payment_periods',
        'estimates.positions.payment_periods.payments',
    ];

    public static function generateNumber(): string
    {
        $record = self::where('number', '!=', null)->orderBy('id', 'desc')->first();
        $newNumber = 1;
        $currentYear = date('y');
        if (!blank($record)) {
            $number = explode('.', $record->number);
            if ($number[1] === $currentYear) {
                $newNumber = ++$number[0];
            }
        }
        return $newNumber . '.' . $currentYear;
    }

    public function scopeAuth()
    {
        if (Auth::user()->is_admin) {
            return $this;
        }
        return $this->where('user_id', Auth::id());
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('id', 'desc');
    }

    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class)->withTrashed();
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }
}
