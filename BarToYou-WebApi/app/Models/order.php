<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $table = 'Order';
    protected $primaryKey = 'id';

    protected $fillable = [
        'member_id',
        'consumption_recipe_id',
        'date_time',
        'quantity',
        'custom_drink_id',
        'status_id',
    ];

    public function members()
    {
        return $this->belongsTo(members::class, 'member_id');
    }

    public function recipes()
    {
        return $this->hasMany(ConsumptionRecipe::class, 'id', 'consumption_recipe_id',);
    }


    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function formattedOrder()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->member_id, // Corrección aquí
            'date_time' => $this->date_time,
            'status' => $this->status->name,
            'items' => $this->recipes->groupBy('consumption_id')->map(function ($group) {
                return [
                    'name' => $group->first()->consumption->name,
                    'ingredients' => $group->map(function ($recipe) {
                        return [
                            'ingredient' => $recipe->ingredient->name,
                            'amount' => $recipe->ingredient_amount . ' ' . $recipe->ingredient_unit
                        ];
                    })->values()
                ];
            })->values()
        ];
    }
}
