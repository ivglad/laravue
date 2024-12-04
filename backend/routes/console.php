<?php

use App\Console\Commands\DeleteOldFiles;
use App\Console\Commands\Import\Import;
use Illuminate\Support\Facades\Schedule;

Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command(DeleteOldFiles::class, ['--path' => 'import/backup', '--days' => '7'])->daily();
Schedule::command(DeleteOldFiles::class, ['--path' => 'app/public/generated', '--days' => '1'])->daily();
Schedule::command(Import::class, ['--all'])->everyTenMinutes();
