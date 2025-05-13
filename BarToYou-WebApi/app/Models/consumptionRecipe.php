<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumptionRecipe extends Model
{
    use HasFactory;

    protected $table = 'ConsumptionRecipe';
    protected $primaryKey = 'id';

    protected $fillable = [
        'consumption_id',
        'ingredient_id',
        'ingredient_amount',
        'ingredient_unit',
        'custom_drink_id',
    ];

    // Relación con Order
    public function order()
    {
        return $this->hasMany(Order::class, 'consumption_recipe_id');
    }
    public function consumption()
    {
        return $this->belongsTo(consumption::class, 'consumption_id');
    }
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }

    public function deleteRelations() {
        error_log("relation consu recip");
        $this->order()->delete();
    }
}
