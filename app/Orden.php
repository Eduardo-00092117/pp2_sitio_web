<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Orden extends Model
{
    protected $table = 'orden';

    public function producto()
    {
        return $this->belongsToMany('App\Producto','orden_detalle', 'orden_id','producto_id')->withPivot('id','cantidad','costo_unitario', 'total');
    }
    
}
