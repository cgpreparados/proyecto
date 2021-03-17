<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialesOperaciones extends Model
{
    protected $table= 'cg.materiales_operaciones';
    protected $fillable=['codigo_material'];
    public $timestamps = false;
}
