<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBOpcion extends Model
{
    protected $table = 'WEB.opciones';
    public $timestamps=false;

    
    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';

    public function grupoopcion()
    {
        return $this->belongsTo('App\WEBGrupoopcion', 'grupoopcion_id', 'id');
    }

    public function rolopcion()
    {
        return $this->hasMany('App\WEBRolOpcion', 'opcion_id', 'id');
    }
    
}
