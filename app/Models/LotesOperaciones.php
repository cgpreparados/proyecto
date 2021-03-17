<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotesOperaciones extends Model
{
    protected $table= 'cg.lotes_operaciones';
    protected $fillable=['lote'];
    public $timestamps = false;

}
