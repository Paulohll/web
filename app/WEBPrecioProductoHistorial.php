<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPrecioProductoHistorial extends Model
{
    protected $table = 'WEB.precioproductohistoriales';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function precioproducto()
    {
        return $this->belongsTo('App\WEBPrecioProducto', 'precioproducto_id', 'id');
    }
    public function producto()
    {
        return $this->belongsTo('App\ALMProducto', 'producto_id', 'COD_PRODUCTO');
    }
}
