<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBGrupoopcion extends Model
{
    protected $table = 'WEB.grupoopciones';
    public $timestamps=false;


    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';

    public function opcion()
    {
        return $this->hasMany('App\WEBOpcion', 'grupoopcion_id', 'id');
    }


}
