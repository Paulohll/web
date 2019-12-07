<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBAutorizacionNotaIngreso extends Model
{
    protected $table = 'WEB.AUTORIZACIONNOTAINGRESO';
    public $timestamps=false;

    protected $primaryKey = 'COD_AUTORIZACION';
    public $incrementing = false;
    public $keyType = 'string';



}
