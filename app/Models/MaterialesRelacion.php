<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialesRelacion extends Model
{
    protected $table= 'cg.materiales_relacion';
    protected $fillable=['codigo_material_entrante','codigo_material_saliente'];
}
