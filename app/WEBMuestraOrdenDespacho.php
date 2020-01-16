<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBMuestraOrdenDespacho extends Model
{
    protected $table = 'WEB.muestraordendespachos';
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';
    
}
