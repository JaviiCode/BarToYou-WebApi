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
            'user_id' => $this->user_id,
            'consumption_recipe_id' => $this->consumption_recipe_id,
            'date_time' => $this->date_time,
            'quantity' => $this->quantity,
            'status_id' => $this->status_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'consumption_recipe' => new consumptionRecipeResource($this->whenLoaded('consumptionRecipe')),
            'status' => new orderStatusResource($this->whenLoaded('status')),
        ];
    }
}
