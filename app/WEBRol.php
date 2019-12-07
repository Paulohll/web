<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBRol extends Model
{
    protected $table = 'WEB.rols';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    
    public function user()
    {
        return $this->hasMany('App\User', 'rol_id', 'id');
    }

    public function rolopcion()
    {
        return $this->hasMany('App\WEBRolOpcion', 'rol_id', 'id');
    }

}
