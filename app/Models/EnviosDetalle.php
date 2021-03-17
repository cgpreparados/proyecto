<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnviosDetalle extends Model
{
    protected $table= 'cg.envios_detalle';
    protected $fillable=['id_envio_detalle'];
    public $timestamps = false;
}
