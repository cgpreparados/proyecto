<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MaterialesStock extends Model
{
    protected $table= 'cg.materiales_stock';
    protected $fillable=['codigo_material'];

    public $timestamps = false;
}
