<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ingredientCategory extends Model
{
    /** @use HasFactory<\Database\Factories\IngredientCategoryFactory> */
    use HasFactory;
    protected $table = 'IngredientCategory';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'category_id');
    }
}
