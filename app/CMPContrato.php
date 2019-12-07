<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CMPContrato extends Model
{
    protected $table = 'CMP.CONTRATO';
    public $timestamps=false;

    protected $primaryKey = 'COD_CONTRATO';
	public $incrementing = false;
    public $keyType = 'string';
    

    public function scopeBusquedaGenerica($query,$criterio,$buscar,$likeOrEqual){

    	if(trim($buscar) != '' and trim($criterio) != ''){
            if ($likeOrEqual=='l') {
                $query->where($criterio, 'like','%'. $buscar.'%');
            }elseif ($likeOrEqual=='e') {
                $query->where($criterio, '=', $buscar);
            }
    		
    	}

    }

    public function documentonotacredito()
    {
        return $this->hasMany('App\WEBDocumentoNotaCredito', 'contrato_id', 'id');
    }


}
