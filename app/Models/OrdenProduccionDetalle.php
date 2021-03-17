<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenProduccionDetalle extends Model
{
    protected $table= 'cg.orden_produccion_detalle';
    protected $fillable=['id_orden'];

    public $timestamps = false;
}
