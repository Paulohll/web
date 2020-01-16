<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBOrdenDespacho extends Model
{
    protected $table = 'WEB.ordendespachos';
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';


    public function contrato()
    {
        return $this->belongsTo('App\CMPContrato', 'contrato_id', 'COD_CONTRATO');
    }

    
}
