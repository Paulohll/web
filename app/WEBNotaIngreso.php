<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBNotaIngreso extends Model
{
    protected $table = 'WEB.notaingreso';
    public $timestamps=false;

    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';


}
