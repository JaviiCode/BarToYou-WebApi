<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class orderResource extends JsonResource
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
            'member_id' => $this->member_id,
            'consumption_recipe_id' => $this->consumption_recipe_id,
            'date_time' => $this->date_time,
            'quantity' => $this->quantity,
            'status_id' => $this->status_id,
            'members' => new membersResource($this->whenLoaded('members')),
            'consumption_recipe' => new consumptionRecipeResource($this->whenLoaded('recipe')),
            'status' => new orderStatusResource($this->whenLoaded('status')),
        ];
    }
}
