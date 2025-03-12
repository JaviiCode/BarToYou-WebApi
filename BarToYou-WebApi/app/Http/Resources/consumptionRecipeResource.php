<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class consumptionRecipeResource extends JsonResource
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
            'consumption_id' => $this->consumption_id,
            'ingredient_id' => $this->ingredient_id,
            'ingredient_amount' => $this->ingredient_amount,
            'ingredient_unit' => $this->ingredient_unit,
            'consumption' => new consumptionResource($this->whenLoaded('consumption')),
            'ingredient' => new ingredientResource($this->whenLoaded('ingredient')),
        ];
    }
}
