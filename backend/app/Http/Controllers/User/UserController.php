<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\VerifyUserPasswordRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\AccessDeniedJsonResponse;
use App\Http\Responses\DeletedJsonResponse;
use App\Models\User;
use App\Notifications\ConfirmationRegistrationNotification;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Получение списка пользователей, применяется фильтрация и пагинация
     *
     * @return UserCollection
     */
    public function index(): UserCollection
    {
        $users = User::with([
            'roles',
            'permissions',
        ])->filter()->paginate($this->perPage);

        return new UserCollection($users);
    }

    /**
     * Создание нового пользователя, для его авторизации необходимо подтвердить владение почтой по ссылке в письме.
     * Пароль пользователя сгенерирован случайным образом.
     *
     * @param UserStoreRequest $request
     * @return UserResource
     * @throws Exception
     */
    public function store(UserStoreRequest $request): UserResource
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->fill($request->validated());
            $password = Str::random(10);
            $user->password = $password;
            $user->verification_token = Str::random(60);
            $user->verification_token_at = Carbon::now()->addHour();
            if (config('app.env') === 'local') {
                $password = 'password';
                $user->email_verified_at = now();
                $user->verification_token = null;
                $user->verification_token_at = null;
            }
            $user->save();

            if (Auth::user()->can('user.update.roles') && !blank($request->role_ids)) {
                $roleIds = $request->role_ids;
            } else {
                $roleIds = Role::where('name', 'user')->pluck('id')->toArray();
            }
            $user->syncRoles($roleIds);
            $user->syncPermissions($user->getPermissionsViaRoles());
            $user->load([
                'roles',
                'permissions',
            ]);
            DB::commit();

            $user->notify(new ConfirmationRegistrationNotification([
                'shortFullName' => getShortFullName($user->toArray()),
                'password' => $password,
                'link' => config('spa.url') . config('spa.paths.verify_email') . '/' . $user->verification_token,
            ]));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new UserResource($user);
    }

    /**
     * Отправка нового кода подтверждения пользователя по ссылке в письме
     *
     * @param User $user
     * @return JsonResponse
     */
    public function sendVerification(User $user): JsonResponse
    {
        if (Auth::user()->can('user.store')) {
            $password = Str::random(10);
            $user->password = $password;
            $user->verification_token = Str::random(60);
            $user->verification_token_at = Carbon::now()->addHour();
            $user->save();

            $user->notify(new ConfirmationRegistrationNotification([
                'shortFullName' => getShortFullName($user->toArray()),
                'password' => $password,
                'link' => config('spa.url') . config('spa.paths.verify_email') . '/' . $user->verification_token,
            ]));
        } else {
            throw new AccessDeniedHttpException();
        }

        return response()->json(['message' => 'Письмо отправлено']);
    }

    /**
     * Подтверждение владением почты по токену, который был отправлен пользователю по ссылке в письме, сохранение пароля пользователя
     *
     * @param VerifyUserPasswordRequest $request
     * @return JsonResponse
     */
    public function verify(VerifyUserPasswordRequest $request): JsonResponse
    {
        $user = User::where('verification_token', $request->token)->first();
        if (!blank($user)) {
            $user->email_verified_at = now();
            $user->verification_token = null;
            $user->verification_token_at = null;
            $user->save();
        } else {
            throw new NotFoundHttpException();
        }

        return response()->json(['message' => 'Аккаунт успешно подтвержден']);
    }

    /**
     * Получение детальной информации по пользователю
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        $user->load([
            'roles',
            'permissions',
        ]);
        return new UserResource($user);
    }

    /**
     * Обновление аккаунта, возможно только своего аккаунта
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return UserResource|AccessDeniedJsonResponse
     * @throws Exception
     */
    public function update(UserUpdateRequest $request, User $user): UserResource|AccessDeniedJsonResponse
    {
        DB::beginTransaction();
        try {
            $user->update($request->validated());
            if (Auth::user()->can('user.update.roles') && !blank($request->role_ids)) {
                $user->syncRoles($request->role_ids);
                $user->syncPermissions($user->getPermissionsViaRoles());
            }
            $user->load([
                'roles',
                'permissions',
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new UserResource($user);
    }

    /**
     * Изменение своего пароля
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->password = $request->password;
        $user->save();
        return response()->json(['message' => 'Пароль изменен']);
    }

    /**
     * Массовое удаление пользователей, если в массиве имеется id авторизованного пользователя, то 422 без удалений
     *
     * @param UserDeleteRequest $request
     * @return JsonResponse
     */
    public function destroy(UserDeleteRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            User::whereIn('id', $request->ids)->delete();
        });

        return new DeletedJsonResponse();
    }

    /**
     * Отправка письма с токеном для сброса пароля по его никнейму
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('username', $request->only('username'))->first();
        $status = Password::sendResetLink(['email' => $user->email]);
        $httpCode = $status === Password::RESET_LINK_SENT ? 200 : 400;
        return new JsonResponse(['message' => __($status)], $httpCode);
    }

    /**
     * Подтверждение владения аккаунта по токену и смена пароля
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where('username', $request->only('username'))->first();
        $credentials = $request->only('password', 'password_confirmation', 'token');
        $credentials['email'] = $user->email;
        $status = Password::reset(
            $credentials,
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        $httpCode = $status === Password::PASSWORD_RESET ? 200 : 400;
        return new JsonResponse(['message' => __($status)], $httpCode);
    }
}
