<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class STDEmpresa extends Model
{
    protected $table = 'STD.EMPRESA';
    public $timestamps=false;
    protected $primaryKey = 'COD_EMPR';
    public $incrementing = false;
    public $keyType = 'string';

    public function userempresacentro()
    {
        return $this->hasMany('App\WEBUserEmpresaCentro', 'empresa_id', 'id');
    }

    public function pedido()
    {
        return $this->hasMany('App\WEBPedido', 'cliente_id', 'id');
    }

    public function regla()
    {
        return $this->hasMany('App\WEBRegla', 'empresa_id', 'id');
    }


    public function ordendespacho()
    {
        return $this->hasMany('App\WEBOrdenDespacho', 'empresa_id', 'id');
    }

}
