<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductosPrecio extends Model
{
    protected $table= 'cg.productos_precio';
    protected $fillable=['id_precio'];
    public $timestamps = false;
}
