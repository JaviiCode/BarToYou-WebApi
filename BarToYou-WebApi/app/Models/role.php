<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;
    protected $table = 'Role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
    ];

    public function members()
    {
        return $this->hasMany(members::class, 'role_id');
    }
}
