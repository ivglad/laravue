<?php

namespace App\Models\Agreement;

use App\Models\Model;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'external_id',
        'number',
        'name',
    ];

    public static function generateNumber(): string
    {
        $record = self::orderBy('id', 'desc')->first();
        $newNumber = 1;
        $currentYear = date('y');
        if (!blank($record)) {
            $number = explode('-', $record->number);
            if ($number[0] === $currentYear) {
                $newNumber = ++$number[1];
            }
        }
        return $currentYear . '-' . $newNumber;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
