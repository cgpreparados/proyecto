<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envios extends Model
{
    protected $table= 'cg.envios';
    protected $fillable=['id_envio'];
    public $timestamps = false;
}
