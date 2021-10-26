<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionUser extends Model
{
    use HasFactory;

    protected $table = 'notificacion_users';

    protected $fillable = [
        'id',
        'producto_id',
        'usuario_id',
        'nombre_producto',
        'precio_producto',
        'descuento_producto',
        'notificacion_leida' 
    ];
}
