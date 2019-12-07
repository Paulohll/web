<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBOrdenDetalleRegla extends Model
{
    protected $table = 'WEB.ordendetallereglas';
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

}
