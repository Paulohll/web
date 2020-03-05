<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBOrdenDespacho extends Model
{
    protected $table = 'WEB.ordendespachos';
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function detalleordendespacho()
    {
        return $this->hasMany('App\WEBDetalleOrdenDespacho', 'ordendespacho_id', 'id')->where('activo','=', 1);
    }
    
}
