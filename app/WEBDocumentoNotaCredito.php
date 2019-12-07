<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBDocumentoNotaCredito extends Model
{
    protected $table = 'WEB.documento_nota_credito';
    public $timestamps=false;


    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';


    public function contrato()
    {
        return $this->belongsTo('App\CMPContrato', 'contrato_id', 'COD_CONTRATO');
    }

}
