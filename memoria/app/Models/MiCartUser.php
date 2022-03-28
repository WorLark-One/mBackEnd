<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiCartUser extends Model
{
    use HasFactory;
    
    protected $table = 'mi_cart_users';

    protected $fillable = [
        'id',
        'producto_id',
        'usuario_id',
        'cantidad'
    ];


}
