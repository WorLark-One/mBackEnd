<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiListaUser extends Model
{
    use HasFactory;
    
    protected $table = 'mi_lista_users';

    protected $fillable = [
        'id',
        'producto_id',
        'usuario_id',
    ];
}
