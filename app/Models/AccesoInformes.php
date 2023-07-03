<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoInformes extends Model
{
    protected $table= 'cg.acceso_informes';
    protected $fillable=['id_acceso'];
    public $timestamps = false;
}
