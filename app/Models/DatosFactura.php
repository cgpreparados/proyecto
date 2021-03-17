<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosFactura extends Model
{
    protected $table= 'cg.datos_factura';
    protected $fillable=['id_timbrado'];
    public $timestamps = false;
}
