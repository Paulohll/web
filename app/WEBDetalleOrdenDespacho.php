<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBDetalleOrdenDespacho extends Model
{
    protected $table = 'WEB.detalleordendespachos';
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';



    
}
