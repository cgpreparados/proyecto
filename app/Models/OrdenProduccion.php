<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenProduccion extends Model
{
    protected $table= 'cg.orden_produccion';
    protected $fillable = [
		 'fecha_inicio','usuario','estado'
	];

    public $timestamps = false;
}
