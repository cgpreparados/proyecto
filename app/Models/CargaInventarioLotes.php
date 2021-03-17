<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaInventarioLotes extends Model
{
    protected $table= 'cg.carga_inventario_lotes';
    protected $fillable=['codigo_material','cantidad','diferencia','user','tipo_inventario'];
    public $timestamps = false;

}
