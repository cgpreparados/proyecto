<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table= 'cg.factura';
    protected $fillable=['id_factura'];
    public $timestamps = false;
}
