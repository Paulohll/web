<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPrecioProducto extends Model
{
    protected $table = 'WEB.precioproductos';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function producto()
    {
        return $this->belongsTo('App\ALMProducto', 'producto_id', 'COD_PRODUCTO');
    }

    public function precioproductohistorial()
    {
        return $this->hasMany('App\WEBPrecioProductoHistorial', 'precioproducto_id', 'id');
    }

}
