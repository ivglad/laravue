<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RoleDeleteRequest;
use App\Http\Requests\User\RoleStoreRequest;
use App\Http\Requests\User\RoleUpdateRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Responses\DeletedJsonResponse;

class RoleController extends Controller
{
    /**
     * Получение списка ролей
     *
     * @return RoleCollection
     */
    public function index(): RoleCollection
    {
        return new RoleCollection(Role::with('permissions')->paginate($this->perPage));
    }

    /**
     * Создание новой роли
     *
     * @param RoleStoreRequest $request
     * @return RoleResource
     */
    public function store(RoleStoreRequest $request): RoleResource
    {
        $attr = $request->validated();
        $attr['guard_name'] = 'web';
        $role = Role::create($attr);
        $permissions = Permission::whereIn('id', $attr['permissions'])->get();
        $role->syncPermissions($permissions);
        $role = $role->load('permissions');
        return new RoleResource($role);
    }

    /**
     * Обновление роли
     *
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return RoleResource|JsonResponse
     */
    public function update(RoleUpdateRequest $request, Role $role): RoleResource|JsonResponse
    {
        $attr = $request->validated();
        if ($role->deletable) {
            $role->update(['name' => $attr['name']]);
            $permissions = Permission::whereIn('id', $attr['permissions'])->get();
            $role->syncPermissions($permissions);
            $role = $role->load('permissions');
            return new RoleResource($role);
        }
        return response()->json(['message' => 'Нельзя изменить предустановленную роль'], 400);
    }

    /**
     * Получение детальной информации по роли
     *
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role): RoleResource
    {
        $role = $role->load('permissions');
        return new RoleResource($role);
    }

    /**
     * Удаление ролей
     *
     * @param RoleDeleteRequest $request
     * @return DeletedJsonResponse|JsonResponse
     */
    public function destroy(RoleDeleteRequest $request): DeletedJsonResponse|JsonResponse
    {
        if (!Role::whereIn('id', $request->ids)->where('deletable', false)->exists()) {
            Role::whereIn('id', $request->ids)->where('deletable', true)->delete();
            return new DeletedJsonResponse();
        }
        return response()->json(['message' => 'Нельзя удалить предустановленную роль'], 400);
    }

}
