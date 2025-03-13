<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\User\RolesAndPermissionsSeeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userAdmin = User::factory()->create([
            'email' => 'admin@example.ltd',
            'username' => 'admin',
        ]);
        $user = User::factory()->create([
            'email' => 'user@example.ltd',
            'username' => 'user',
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        $roleAdmin = Role::where('name', 'admin')->first();
        $roleUser = Role::where('name', 'user')->first();
        $userAdmin->assignRole($roleAdmin);
        $user->assignRole($roleUser);
    }
}
