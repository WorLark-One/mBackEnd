<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompra extends Model
{
    use HasFactory;
    
    protected $table = 'solicitudes_de_compra';

    protected $fillable = [
        'id',
        'usuario_id',
        'direccion',
        'numero',
        'otra_info',
        'celular',
        'region_id',
        'comuna_id',
        'pedido', 
        'estado_id'
    ];


}
