<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->roles() as $fields) {
            Role::updateOrCreate($fields);
        }

        foreach ($this->permissions() as $subject => $permissions) {
            foreach ($permissions as $permission) {
                Permission::updateOrCreate([
                    'name' => $subject . '.' . $permission['action'],
                    'name_translate' => $permission['translate'],
                ]);
            }
        }

        $this->setAdminPermissions();
    }

    public function setAdminPermissions(): void
    {
        $roleAdmin = Role::where('name', 'admin')->first();
        foreach ($this->permissions() as $subject => $permissions) {
            foreach ($permissions as $permission) {
                $roleAdmin->givePermissionTo(Permission::where('name', $subject . '.' . $permission['action'])->first());
            }
        }
    }

    public function roles(): array
    {
        return [
            [
                'name' => 'user',
                'name_translate' => 'Зарегистрированный пользователь',
                'deletable' => false,
            ], [
                'name' => 'admin',
                'name_translate' => 'Администратор',
                'deletable' => false,
            ],
        ];
    }

    public function permissions(): array
    {
        return [
            'user' => [
                ['action' => 'store', 'translate' => 'создание аккаунтов'],
                ['action' => 'update', 'translate' => 'обновление своего аккаунта'],
                ['action' => 'update.other', 'translate' => 'обновление других аккаунтов'],
                ['action' => 'update.roles', 'translate' => 'изменение ролей аккаунтов'],
                ['action' => 'destroy', 'translate' => 'удаление аккаунтов'],
            ],
            'role' => [
                ['action' => 'store', 'translate' => 'создание ролей'],
                ['action' => 'update', 'translate' => 'обновление ролей'],
                ['action' => 'destroy', 'translate' => 'удаление ролей'],
            ],
            'comment' => [
                ['action' => 'destroy.other', 'translate' => 'удаление чужих комментариев'],
            ],
            'activity' => [
                ['action' => 'index', 'translate' => 'просмотр журнала изменений'],
            ],
        ];
    }
}
