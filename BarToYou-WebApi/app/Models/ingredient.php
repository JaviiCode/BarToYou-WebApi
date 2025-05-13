<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ingredient extends Model
{
    /** @use HasFactory<\Database\Factories\IngredientFactory> */
    use HasFactory;
    protected $table = 'Ingredient';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'stock',
        'ingredient_unit',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(IngredientCategory::class, 'category_id');
    }

    public function recipes()
    {
        return $this->hasMany(consumptionRecipe::class, 'ingredient_id');
    }

    public function deleteRelations() {
        $recipes = $this->recipes();

        foreach ($recipes->get() as $reci) {
            $reci->deleteRelations();
        }

        $recipes->delete();
    }
}
