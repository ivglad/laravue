<?php

namespace Database\Seeders;

use App\Models\Agreement\Agreement;
use App\Models\Comment\Comment;
use App\Models\Handbook\Counterparty;
use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'surname' => 'Analyticum',
            'patronymic' => '',
            'username' => 'admin',
            'email' => 'admin@admin.ru',
            'is_admin' => true,
            'job' => 'Admin',
        ]);
        User::factory(10)->create();
    }
}
