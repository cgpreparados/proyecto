<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiales extends Model
{
    protected $table= 'cg.materiales';
    protected $fillable=['cod_material','activo'];
    public $timestamps = false;
}
 