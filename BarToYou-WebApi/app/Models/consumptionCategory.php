<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumptionCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumptionCategoryFactory> */
    use HasFactory;
    protected $table = 'ConsumptionCategory';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function consumptions()
    {
        return $this->hasMany(Consumption::class, 'category_id');
    }

    public function deleteRelations() {
        $consumitions = $this->consumptions();

        foreach ($consumitions->get() as $consu) {
            $consu->deleteRelations();
        }

        $consumitions->delete();
    }
}
