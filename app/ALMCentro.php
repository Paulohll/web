<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ALMCentro extends Model
{
    protected $table = 'ALM.CENTRO';
    public $timestamps=false;
    protected $primaryKey = 'COD_CENTRO';
	public $incrementing = false;
	public $keyType = 'string';

	public function userempresacentro()
    {
        return $this->hasMany('App\WEBUserEmpresaCentro', 'centro_id', 'id');
    }

    public function ordendespacho()
    {
        return $this->hasMany('App\WEBOrdenDespacho', 'centro_id', 'id');
    }


}



