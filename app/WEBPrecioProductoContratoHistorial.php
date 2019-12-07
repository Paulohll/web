<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPrecioProductoContratoHistorial extends Model
{
    protected $table = 'WEB.precioproductocontratohistoriales';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function precioproductocontrato()
    {
        return $this->belongsTo('App\WEBPrecioProductoContrato', 'precioproductocontrato_id', 'id');
    }
    public function producto()
    {
        return $this->belongsTo('App\ALMProducto', 'producto_id', 'COD_PRODUCTO');
    }
}
