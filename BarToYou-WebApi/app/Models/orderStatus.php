<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderStatus extends Model
{
    /** @use HasFactory<\Database\Factories\OrderStatusFactory> */
    use HasFactory;
    protected $table = 'OrderStatus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }

    public function deleteRelations() {
        $orders = $this->orders();

        $orders->delete();
    }
}
