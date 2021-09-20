<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoracionProducto extends Model
{
    use HasFactory;

    protected $table = 'valoracion_productos';

    protected $fillable = [
        'id',
        'value',
        'comentario',
        'nombre_usuario',
        'nombre_producto',
        'producto_id',
        'usuario_id'
    ];
}
