<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CMPCategoria extends Model
{
    protected $table = 'CMP.CATEGORIA';
    public $timestamps=false;

    protected $primaryKey = 'COD_CATEGORIA';
	public $incrementing = false;
    public $keyType = 'string';
    

}



