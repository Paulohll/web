<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBOvFac extends Model
{
    protected $table = 'WEB.OV_FAC';
    public $timestamps=false;

    protected $primaryKey = 'COD_DOCUMENTO_CTBLE';
	public $incrementing = false;
	public $keyType = 'string';

}
