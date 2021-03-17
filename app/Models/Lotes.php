<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lotes extends Model
{
    protected $table= 'cg.lotes';
    protected $fillable=['lote_nro'];
    public $timestamps = false;
}
