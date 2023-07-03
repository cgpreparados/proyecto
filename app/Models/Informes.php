<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informes extends Model
{
    protected $table= 'cg.informes';
    protected $fillable=['id_informe'];
    public $timestamps = false;
}
