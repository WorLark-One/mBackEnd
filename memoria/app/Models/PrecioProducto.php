<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioProducto extends Model
{
    use HasFactory;

    protected $table = 'precio_productos';

    protected $fillable = [
        'id',
        'producto_id',
        'precio',
        'fecha'
    ];
}
