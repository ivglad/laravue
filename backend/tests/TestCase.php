<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('key:generate');
        Artisan::call('config:clear');
        Artisan::call('migrate:refresh');
        $this->user = User::factory()->create();
        $this->role = Role::create([
            'name' => 'user',
            'name_translate' => 'user',
        ]);
        $this->user->roles()->attach($this->role);
        $this->token = 'Bearer ' . $this->user->createToken('API Token')->plainTextToken;
    }

    protected function setPermissionForUser(string $name): void
    {
        $permission = Permission::create([
            'name' => $name,
            'name_translate' => $name,
        ]);
        $this->role->givePermissionTo($permission);
    }

    protected function revokePermissionForUser(string $name): void
    {
        $permission = Permission::where('name', $name)->first();
        $this->role->revokePermissionTo($permission);
    }
}
