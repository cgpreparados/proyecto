<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaInventario extends Model
{
    protected $table= 'cg.carga_inventario';
    protected $fillable=['codigo_material','cantidad','diferencia','user','tipo_inventario'];
    public $timestamps = false;

}
