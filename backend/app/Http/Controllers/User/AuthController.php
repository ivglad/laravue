<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Responses\NotExistsJsonResponse;
use App\Mail\RequestPassword;
use Illuminate\Auth\AuthenticationException;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function login(AuthRequest $request): AuthResource
    {
        Auth::attempt($request->validated());
        if (Auth::check()){
            return new AuthResource(Auth::user());
        }
        return throw (new AuthenticationException());
    }

    /**
     * Log out
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return new JsonResponse(['message' => 'Вы успешно вышли из системы']);
    }

    public function requestPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $attr = $request->validated();
        $user = User::where('username', $attr['username'])->first();
        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->first();
        if (!blank($user)) {
            Mail::to($admin->email)->queue(new RequestPassword($user));
            return new JsonResponse(['message' => 'Запрос на смену пароля будет отправлено администратору портала']);
        }
        return new NotExistsJsonResponse();
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('username', $request->only('username'))->first();
        if (!blank($user)) {
            $status = Password::sendResetLink(['email' => $user->email]);

            $httpCode = $status === Password::RESET_LINK_SENT ? 200 : 400;
            return new JsonResponse(['message' => __($status)], $httpCode);
        }
        return new NotExistsJsonResponse();
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where('username', $request->only('username'))->first();
        if (!blank($user)) {
            $credentials = $request->only('password', 'password_confirmation', 'token');
            $credentials['email'] = $user->email;
            $status = Password::reset(
                $credentials,
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
                    $user->save();
                    event(new PasswordReset($user));
                }
            );

            $httpCode = $status === Password::PASSWORD_RESET ? 200 : 400;
            return new JsonResponse(['message' => __($status)], $httpCode);
        }
        return new NotExistsJsonResponse();
    }
}
