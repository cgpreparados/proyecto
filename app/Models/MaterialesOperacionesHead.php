<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialesOperacionesHead extends Model
{
    protected $table= 'cg.materiales_operaciones_head';
    protected $fillable=['codigo_material'];
    public $timestamps = false;
}
