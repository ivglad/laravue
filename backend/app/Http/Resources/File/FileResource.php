<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'created_at' => $this->created_at,
            'size' => $this->size,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'collection' => $this->collection_name,
            'sort' => $this->order_column,
        ];
    }
}
