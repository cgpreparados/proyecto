<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    protected $table= 'cg.compras';
    protected $fillable=['id_compras'];
    public $timestamps = false;
}
