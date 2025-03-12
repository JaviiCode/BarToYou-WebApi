<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumptionRecipe extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumptionRecipeFactory> */
    use HasFactory;
    protected $table = 'ConsumptionRecipe';
    protected $primaryKey = 'id';

    protected $fillable = [
        'consumption_id',
        'ingredient_id',
        'ingredient_amount',
        'ingredient_unit',
    ];

    public function consumption()
    {
        return $this->belongsTo(Consumption::class, 'consumption_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
