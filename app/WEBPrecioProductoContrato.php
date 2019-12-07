<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPrecioProductoContrato extends Model
{
    protected $table = 'WEB.precioproductocontratos';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function producto()
    {
        return $this->belongsTo('App\ALMProducto', 'producto_id', 'COD_PRODUCTO');
    }

    public function precioproductocontratohistorial()
    {
        return $this->hasMany('App\WEBPrecioProductoContratoHistorial', 'precioproductocontrato_id', 'id');
    }

}
