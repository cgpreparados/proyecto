<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprasDetalle extends Model
{
    protected $table= 'cg.compras_detalle';
    protected $fillable=['id_compra'];
    public $timestamps = false;
}
