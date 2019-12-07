<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBDocDoc extends Model
{
    protected $table = 'WEB.DOC_DOC';
    public $timestamps=false;

    protected $primaryKey = 'COD_DOCUMENTO_CTBLE';
	public $incrementing = false;
	public $keyType = 'string';

}
