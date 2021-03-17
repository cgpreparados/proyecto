<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotesEnvios extends Model
{
    protected $table= 'cg.lotes_envios';
    protected $fillable=['id_lote_envio'];
    public $timestamps = false;
}
