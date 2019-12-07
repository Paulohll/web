<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBPedido extends Model
{
    protected $table = 'WEB.pedidos';
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


    public function empresa()
    {
        return $this->belongsTo('App\STDEmpresa', 'cliente_id', 'COD_EMPR');
    }
    public function estadocat()
    {
        return $this->belongsTo('App\CMPCategoria', 'estado_id', 'COD_CATEGORIA');
    }
    public function condicionpago()
    {
        return $this->belongsTo('App\CMPCategoria', 'tipopago_id', 'COD_CATEGORIA');
    }
    public function direccionentrega()
    {
        return $this->belongsTo('App\STDEmpresaDireccion', 'direccion_entrega_id', 'COD_DIRECCION');
    }

    public function detallepedido()
    {
        return $this->hasMany('App\WEBDetallePedido', 'pedido_id', 'id')->where('activo','=', 1);
    }
    
}
