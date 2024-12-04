<?php

namespace App\Http\Resources\Order;

use App\Enums\OrderStatus;
use App\Http\Resources\Agreement\AgreementResource;
use App\Http\Resources\Comments\CommentCollection;
use App\Http\Resources\File\FileCollection;
use App\Http\Resources\Handbook\CounterpartyResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'number' => $this->number,
            'counterparty' => new CounterpartyResource($this->whenLoaded('counterparty')),
            'agreement' => new AgreementResource($this->whenLoaded('agreement')),
            'status' => [
                'id' => $this->status,
                'name' => OrderStatus::from($this->status)->name(),
            ],
            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => $this->whenNotNull((new CommentCollection($this->whenLoaded('comments')))->collection),
            'estimates' => $this->whenNotNull((new EstimateCollection($this->whenLoaded('estimates')))->collection),
            'files' => $this->whenNotNull((new FileCollection($this->whenLoaded('media')))->collection),
        ];
    }
}
