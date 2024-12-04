<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\AccessDeniedJsonResponse;
use App\Http\Responses\DeletedJsonResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): UserCollection
    {
        return new UserCollection(User::filter()->paginate($this->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): UserResource|AccessDeniedJsonResponse
    {
        if (!Auth::user()->is_admin) {
            return new AccessDeniedJsonResponse();
        }
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): UserResource|AccessDeniedJsonResponse
    {
        if (Auth::id() !== $user->id && !Auth::user()->is_admin) {
            return new AccessDeniedJsonResponse();
        }
        $attr = $request->validated();
        if (!Auth::user()->is_admin && $attr['is_admin']) {
            return new AccessDeniedJsonResponse();
        }
        $user->update($attr);
        return new UserResource($user);
    }

    /**
     * Update password of the specified resource in storage.
     */
    public function changePassword(PasswordRequest $request, User $user): JsonResponse|AccessDeniedJsonResponse
    {
        if (Auth::id() === $user->id || Auth::user()->is_admin) {
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return new JsonResponse(['message' => 'Пароль изменен']);
        }

        return new AccessDeniedJsonResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): DeletedJsonResponse|AccessDeniedJsonResponse
    {
        if (Auth::id() === $user->id || !Auth::user()->is_admin) {
            return new AccessDeniedJsonResponse();
        }
        $user->delete();
        return new DeletedJsonResponse();
    }
}
