<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return config('app.spa.url') . config('app.spa.restore_path') . '?token=' . $token;
        });

        if (env('LOG_QUERIES', false)) {
            DB::listen(function ($query) {
                $time = $query->time / 1000; // sec
                if ($time >= 0.5) {
                    Log::channel('queries')->debug(Str::replaceArray('?', $query->bindings, $query->sql));
                    Log::channel('queries')->debug($time);
                }
            });
        }
    }
}
