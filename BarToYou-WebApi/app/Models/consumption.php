<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumption extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumptionFactory> */
    use HasFactory;
    protected $table = 'Consumption';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ConsumptionCategory::class, 'category_id');
    }

    public function recipes()
    {
        return $this->hasMany(ConsumptionRecipe::class, 'consumption_id');
    }
}
