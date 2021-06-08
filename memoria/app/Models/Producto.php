<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //use HasFactory;
    protected $table = 'producto';

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'imagen',
        'ubicacion',
        'link',
        'marketplace'
    ];
}
