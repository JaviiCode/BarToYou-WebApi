<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class membersResource extends JsonResource
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
            'token' => $this->token,
            'expiration_date_token' => $this->expiration_date_token,
            'role' => new RoleResource($this->whenLoaded('role')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
        ];
    }
}
