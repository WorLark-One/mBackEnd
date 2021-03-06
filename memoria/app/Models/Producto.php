<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //use HasFactory;
    protected $table = 'producto';

    protected $fillable = [
        'id',
        'titulo',
        'descripcion',
        'precio',
        'imagen',
        'ubicacion',
        'link',
        'marketplace',
        'valoracion',
        'cantidad_valoraciones',
        'visualizaciones',
        'puntaje_tendencia',
        'descuento',
        'fecha_descuento'
    ];
}
