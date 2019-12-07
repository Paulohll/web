<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class STDEmpresaDireccion extends Model
{
    protected $table = 'STD.EMPRESA_DIRECCION';
    public $timestamps=false;
    protected $primaryKey = 'COD_DIRECCION';
	public $incrementing = false;
	public $keyType = 'string';


	public function pedido()
    {
        return $this->hasMany('App\WEBPedido', 'direccion_entrega_id', 'id');
    }
    public function distrito()
    {
        return $this->belongsTo('App\CMPCategoria', 'COD_DISTRITO', 'COD_CATEGORIA');
    }

}
