<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ingredientResource extends JsonResource
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
            'stock' => $this->stock,
            'ingredient_unit' => $this->ingredient_unit,
            'category_id' => $this->category_id,
            'category' => new ingredientCategoryResource($this->whenLoaded('category')),
        ];
    }
}
