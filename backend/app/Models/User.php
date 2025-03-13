<?php

namespace App\Models;

use App\Http\Filters\User\UserFilter;
use App\Notifications\ResetPasswordNotification;
use App\Pipes\RemoveHiddenFieldsFromLogChangesPipe;
use App\Traits\Models\Filterable;
use App\Traits\Models\GetHiddenFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia,
        HasFactory,
        Notifiable,
        HasRoles,
        HasApiTokens,
        SoftDeletes,
        LogsActivity,
        Filterable,
        GetHiddenFields;

    protected static string $filterModel = UserFilter::class;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'email',
        'username',
        'password',
        'email_verified_at',
        'verification_token',
        'hex_color',
        'phone',
        'job_title',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'permission'
    ];

    public function getPermissionAttribute()
    {
        return $this->getAllPermissions();
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = config('spa.url') . config('spa.paths.reset_password') . '?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }

    protected static function booted(): void
    {
        static::addLogChange(new RemoveHiddenFieldsFromLogChangesPipe);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => Activity::logEventNameToRu($eventName))
            ->logFillable()
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'password',
                'remember_token',
                'verification_token',
                'email_verified_at',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
