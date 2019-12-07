<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBOcOv extends Model
{
    protected $table = 'WEB.OC_OV';
    public $timestamps=false;

    protected $primaryKey = 'COD_ORDEN';
	public $incrementing = false;
	public $keyType = 'string';

}
