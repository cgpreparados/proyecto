<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    protected $table= 'cg.factura_detalles';
    protected $fillable=['id_factura_detalle'];
    public $timestamps = false;
}
