<?php

namespace Http\Controllers\User;

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    public function testDestroy()
    {
        $role = Role::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $this->setPermissionForUser('role.destroy');
        $response = $this->delete(route('role.destroy'), [
            'ids' => [$role->id]
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Успешно удалено');
        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function testDestroyWithoutPermission()
    {
        $role = Role::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $this->revokePermissionForUser('role.destroy');
        $response = $this->delete(route('role.destroy'), [
            'ids' => [$role->id]
        ], ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Доступ запрещен');
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
        ]);
    }

    public function testDestroyNotDeletable()
    {
        $role = Role::create([
            'name' => 'test123',
            'name_translate' => 'test123',
            'deletable' => false,
        ]);
        $this->setPermissionForUser('role.destroy');
        $response = $this->delete(route('role.destroy'), [
            'ids' => [$role->id]
        ], ['Authorization' => $this->token]);
        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Нельзя удалить предустановленную роль');
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('role.index'), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'name_translate',
                    'deletable',
                    'permissions' => [
                        '*' => [
                            'id',
                            'name',
                            'name_translate',
                        ],
                    ],
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    }

    public function testShow()
    {
        $response = $this->get(route('role.show', $this->role->id), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'name_translate',
                'deletable',
                'permissions' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                    ],
                ],
            ],
        ]);
        $response->assertJsonPath('data.id', $this->role->id);
    }

    public function testStore()
    {
        $permission = Permission::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $data = [
            'name' => 'test',
            'name_translate' => 'test',
            'permissions' => [
                $permission->id,
            ],
        ];
        $this->setPermissionForUser('role.store');
        $response = $this->post(route('role.store'), $data, ['Authorization' => $this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'name_translate',
                'deletable',
                'permissions' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                    ],
                ],
            ],
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }

    public function testStoreWithoutPermission()
    {
        $permission = Permission::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $data = [
            'name' => 'test',
            'name_translate' => 'test',
            'permissions' => [
                $permission->id,
            ],
        ];
        $countRoles = Role::count();
        $this->revokePermissionForUser('role.store');
        $response = $this->post(route('role.store'), $data, ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Доступ запрещен');
        $this->assertEquals($countRoles, Role::count());
    }

    public function testUpdate()
    {
        $permission = Permission::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $data = [
            'name' => 'test',
            'name_translate' => 'test',
            'permissions' => [
                $permission->id,
            ],
        ];
        $this->setPermissionForUser('role.update');
        $response = $this->put(route('role.update', $this->role->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'name_translate',
                'deletable',
                'permissions' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                    ],
                ],
            ],
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }

    public function testUpdateNotDeletable()
    {
        $permission = Permission::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $role = Role::create([
            'name' => 'test123',
            'name_translate' => 'test123',
            'deletable' => false,
        ]);
        $data = [
            'name' => 'test',
            'name_translate' => 'test',
            'permissions' => [
                $permission->id,
            ],
        ];
        $this->setPermissionForUser('role.update');
        $response = $this->put(route('role.update', $role->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Нельзя изменить предустановленную роль');
        $this->assertDatabaseHas('roles', [
            'name' => 'test123',
        ]);
    }

    public function testUpdateWithoutPermission()
    {
        $permission = Permission::create([
            'name' => 'test123',
            'name_translate' => 'test123',
        ]);
        $data = [
            'name' => 'test',
            'name_translate' => 'test',
            'permissions' => [
                $permission->id,
            ],
        ];
        $this->revokePermissionForUser('role.update');
        $response = $this->put(route('role.update', $this->role->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Доступ запрещен');
        $this->assertDatabaseMissing('roles', [
            'name' => $data['name'],
        ]);
    }
}
