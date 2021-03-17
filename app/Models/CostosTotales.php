<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostosTotales extends Model
{
    protected $table= 'cg.costos_totales';
    protected $fillable=['id_costo'];
    public $timestamps = false;
}
