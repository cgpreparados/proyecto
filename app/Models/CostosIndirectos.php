<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostosIndirectos extends Model
{
    protected $table= 'cg.costos_indirectos';
    protected $fillable=['id_costo_indirecto'];
    public $timestamps = false;
}
