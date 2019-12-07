<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBDetalleDocumentoAsociados extends Model
{
    protected $table = 'WEB.detalle_documento_asociados';
    public $timestamps=false;


    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';


}
