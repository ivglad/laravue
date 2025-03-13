<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_translate' => $this->name_translate,
            'deletable' => $this->deletable,
            'permissions' => $this->whenNotNull((new PermissionCollection($this->whenLoaded('permissions')))->collection),
        ];
    }

}
