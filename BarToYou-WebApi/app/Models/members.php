<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class members extends Model
{
    /** @use HasFactory<\Database\Factories\MembersFactory> */
    use HasFactory;
    protected $table = 'Members';

    protected $fillable = [
        'name',
        'password',
        'token',
        'expiration_date_token',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'member_id', "id");
    }
}
