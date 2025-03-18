<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Responses\WrongAuthJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Авторизация по никнейму и паролю, генерация токена Bearer
     *
     * @param AuthRequest $request
     * @return WrongAuthJsonResponse|AuthResource
     * @throws AuthenticationException
     */
    public function login(AuthRequest $request): WrongAuthJsonResponse|AuthResource
    {
        Auth::attempt($request->validated());
        if (Auth::check()){
            $user = Auth::user();
            $user->load(['roles', 'permissions']);
            return new AuthResource($user);
        }
        return new WrongAuthJsonResponse();
    }

    /**
     * Выход из системы, удаление токенов Bearer
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Вы успешно вышли из системы']);
    }
}
