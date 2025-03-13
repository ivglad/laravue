<?php

namespace Http\Controllers\User;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function testIndex()
    {
        $permission = Permission::create([
            'name' => 'user.index',
            'name_translate' => 'Получить список пользователей',
        ]);
        $this->role->givePermissionTo($permission);
        foreach (range(1, 50) as $i) {
            $user = User::factory()->create();
            $user->roles()->attach($this->role);
        }
        $response = $this->get(route('user.index'), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'surname',
                    'name',
                    'patronymic',
                    'email',
                    'username',
                    'hex_color',
                    'phone',
                    'job_title',
                    'roles' => [
                        '*' => [
                            'id',
                            'name',
                            'name_translate',
                            'permissions' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'name_translate',
                                ],
                            ],
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
        $this->setPermissionForUser('user.show');
        $response = $this->get(route('user.show', $this->user->id), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'surname',
                'name',
                'patronymic',
                'email',
                'username',
                'hex_color',
                'phone',
                'job_title',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                        'permissions' => [
                            '*' => [
                                'id',
                                'name',
                                'name_translate',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $response->assertJsonPath('data.id', 1);
        $response->assertJsonPath('data.roles.0.id', 1);
        $response->assertJsonPath('data.roles.0.name', 'user');
        $response->assertJsonPath('data.roles.0.permissions.0.id', 1);
        $response->assertJsonPath('data.roles.0.permissions.0.name', 'user.show');
    }

    public function testDestroyWithoutPermission()
    {
        $count = random_int(5, 10);
        foreach (range(1, $count) as $i) {
            $ids[] = User::factory()->create()->id;
        }
        $this->revokePermissionForUser('user.destroy');
        $response = $this->delete(route('user.destroy'), [
            'ids' => $ids,
        ], ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Доступ запрещен');
        $this->assertDatabaseHas('users', [
            'id' => $ids[0],
        ]);
    }

    public function testDestroy()
    {
        $count = random_int(5, 10);
        foreach (range(1, $count) as $i) {
            $ids[] = User::factory()->create()->id;
        }
        $this->setPermissionForUser('user.destroy');
        $response = $this->delete(route('user.destroy'), [
            'ids' => $ids,
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Успешно удалено');
        $deletedUsers = User::whereIn('id', $ids)->get();
        $this->assertCount(0, $deletedUsers);
        $this->assertSoftDeleted('users', ['id' => $ids[0]]);
    }

    public function testStore()
    {
        $data = [
            'surname' => 'Фомина',
            'name' => 'Трофим',
            'patronymic' => null,
            'email' => 'admin123@example.ltd',
            'username' => 'admin123',
            'hex_color' => '#324324',
            'phone' => '+7 (922) 905-6898',
            'job_title' => 'Пианист',
            'role_ids' => [
                1
            ],
        ];
        $this->setPermissionForUser('user.store');
        $response = $this->post(route('user.store'), $data, ['Authorization' => $this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'surname',
                'name',
                'patronymic',
                'email',
                'username',
                'hex_color',
                'phone',
                'job_title',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                        'permissions' => [
                            '*' => [
                                'id',
                                'name',
                                'name_translate',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertDatabaseHas('users', [
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'email' => $data['email'],
            'username' => $data['username'],
            'hex_color' => $data['hex_color'],
            'phone' => $data['phone'],
            'job_title' => $data['job_title'],
        ]);
    }

    public function testStoreWithoutPermission()
    {
        $data = [
            'surname' => 'Фомина',
            'name' => 'Трофим',
            'patronymic' => null,
            'email' => 'admin123@example.ltd',
            'username' => 'admin123',
            'hex_color' => '#324324',
            'phone' => '+7 (922) 905-6898',
            'job_title' => 'Пианист',
            'role_ids' => [
                1
            ],
        ];
        $this->revokePermissionForUser('user.store');
        $response = $this->post(route('user.store'), $data, ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Доступ запрещен');
    }

    public function testUpdateSelf()
    {
        $data = [
            'surname' => 'Фомина',
            'name' => 'Трофим',
            'patronymic' => null,
            'email' => 'admin123@example.ltd',
            'username' => 'admin123',
            'hex_color' => '#324324',
            'phone' => '+7 (922) 905-6898',
            'job_title' => 'Пианист',
            'role_ids' => [
                1
            ],
        ];
        $this->setPermissionForUser('user.update');
        $response = $this->put(route('user.update', $this->user->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'surname',
                'name',
                'patronymic',
                'email',
                'username',
                'hex_color',
                'phone',
                'job_title',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                        'permissions' => [
                            '*' => [
                                'id',
                                'name',
                                'name_translate',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertDatabaseHas('users', [
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'email' => $data['email'],
            'username' => $data['username'],
            'hex_color' => $data['hex_color'],
            'phone' => $data['phone'],
            'job_title' => $data['job_title'],
        ]);
    }

    public function testUpdateSelfWithoutPermission()
    {
        $data = [
            'surname' => 'Фомина',
            'name' => 'Трофим',
            'patronymic' => null,
            'email' => 'admin123@example.ltd',
            'username' => 'admin123',
            'hex_color' => '#324324',
            'phone' => '+7 (922) 905-6898',
            'job_title' => 'Пианист',
            'role_ids' => [
                1
            ],
        ];
        $this->revokePermissionForUser('user.update');
        $response = $this->put(route('user.update', $this->user->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Доступ запрещен');
    }

    public function testUpdateOtherWithoutPermission()
    {
        $data = [
            'surname' => 'Фомина',
            'name' => 'Трофим',
            'patronymic' => null,
            'email' => 'admin123@example.ltd',
            'username' => 'admin123',
            'hex_color' => '#324324',
            'phone' => '+7 (922) 905-6898',
            'job_title' => 'Пианист',
            'role_ids' => [
                1
            ],
        ];
        $user = User::factory()->create();
        $this->revokePermissionForUser('user.update.other');
        $response = $this->put(route('user.update', $user->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Доступ запрещен');
    }

    public function testUpdateOther()
    {
        $data = [
            'surname' => 'Фомина',
            'name' => 'Трофим',
            'patronymic' => null,
            'email' => 'admin123@example.ltd',
            'username' => 'admin123',
            'hex_color' => '#324324',
            'phone' => '+7 (922) 905-6898',
            'job_title' => 'Пианист',
            'role_ids' => [
                1
            ],
        ];
        $user = User::factory()->create();
        $this->setPermissionForUser('user.update.other');
        $response = $this->put(route('user.update', $user->id), $data, ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'surname',
                'name',
                'patronymic',
                'email',
                'username',
                'hex_color',
                'phone',
                'job_title',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'name_translate',
                        'permissions' => [
                            '*' => [
                                'id',
                                'name',
                                'name_translate',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertDatabaseHas('users', [
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'email' => $data['email'],
            'username' => $data['username'],
            'hex_color' => $data['hex_color'],
            'phone' => $data['phone'],
            'job_title' => $data['job_title'],
        ]);
    }

    public function testForgotPassword()
    {
        $response = $this->post(route('password.forgot'), [
            'username' => $this->user->username,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Мы отправили вам ссылку для сброса пароля по электронной почте.');

    }

    public function testVerify()
    {
        $this->user->verification_token = Str::random(60);
        $this->user->verification_token_at = now();
        $this->user->email_verified_at = null;
        $this->user->save();
        $response = $this->post(route('auth.verify'), [
            'token' => $this->user->verification_token,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Аккаунт успешно подтвержден');
        $this->user->refresh();
        $this->assertNotEquals(null, $this->user->email_verified_at);
        $this->assertEquals(null, $this->user->verification_token);
    }

    public function testVerifyWithWrongToken()
    {
        $response = $this->post(route('auth.verify'), [
            'token' => Str::random(60),
        ]);
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Запись не найдена');
    }

    public function testChangePassword()
    {
        $oldHashedPassword = $this->user->password;
        $response = $this->patch(route('user.change-password'), [
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Пароль изменен');
        $this->assertNotEquals($oldHashedPassword, $this->user->fresh()->password);
    }

    public function testSendVerification()
    {
        $user = User::factory()->create();
        $oldHashedPassword = $user->password;
        $oldToken = $user->verification_token;
        $this->setPermissionForUser('user.store');
        $response = $this->patch(route('user.send-verification', $user->id), [], ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Письмо отправлено');
        $this->assertNotEquals($oldHashedPassword, $user->fresh()->password);
        $this->assertNotEquals($oldToken, $user->fresh()->verification_token);
    }

    public function testSendVerificationWithoutPermission()
    {
        $user = User::factory()->create();
        $this->revokePermissionForUser('user.store');
        $response = $this->patch(route('user.send-verification', $user->id), [], ['Authorization' => $this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Доступ запрещен');
    }

    public function testResetPasswordWrongToken()
    {
        Password::sendResetLink(['email' => $this->user->email]);
        $oldHashedPassword = $this->user->password;
        $response = $this->post(route('password.reset'), [
            'token' => Str::random(60),
            'username' => $this->user->username,
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonPath('message', 'Этот токен сброса пароля недействителен.');
        $this->assertEquals($oldHashedPassword, $this->user->fresh()->password);
    }
}
