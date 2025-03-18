<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class VerificationCodeClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verification-code-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очищает просроченные коды подтверждения почты пользователей';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::where('verification_token_at', '<=', Carbon::now())
            ->update(['verification_token' => null, 'verification_token_at' => null]);
    }
}
