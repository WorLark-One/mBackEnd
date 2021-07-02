<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;
    
    protected $table = 'comunas';

    protected $fillable = [
        'id',
        'nombre',
        'region',
    ];

    //public function comunaRegion(): BelongsTo
    //{
        //return $this->belongsTo(Region::class);
    //}
}
