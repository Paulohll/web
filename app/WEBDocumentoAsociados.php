<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBDocumentoAsociados extends Model
{
    protected $table = 'WEB.documento_asociados';
    public $timestamps=false;


    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';


}
