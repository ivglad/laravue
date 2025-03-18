<?php

use App\Console\Commands\User\VerificationCodeClear;
use Illuminate\Support\Facades\Schedule;

Schedule::command(VerificationCodeClear::class)->everyMinute();
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
