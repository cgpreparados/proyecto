<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $table= 'cg.proveedor';
    protected $fillable=['id_proveedor'];
    public $timestamps = false;
}
