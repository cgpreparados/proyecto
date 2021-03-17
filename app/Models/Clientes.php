<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table= 'cg.clientes';
    protected $fillable=['id_cliente'];
    public $timestamps = false;
}
