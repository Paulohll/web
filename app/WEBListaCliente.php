<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
class WEBListaCliente extends Model
{
    protected $table = 'WEB.LISTACLIENTE';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    public function scopeName($query,$name){

    	if(trim($name) != ''){
    		$query->where('NOM_EMPR', '=', $name);
    	}

    }

    public function scopeTipodocumento($query,$tipo){

    	if(trim($tipo) != ''){
    		$query->where('COD_TIPO_DOCUMENTO', '=', $tipo);
    	}

    }

    public function scopeCanal($query,$canal){
        if(trim($canal) != ''){
            $query->where('COD_CATEGORIA_CANAL_VENTA', '=', $canal);
        }
    }

    public function scopeCliente($query,$cliente){
        if(trim($cliente) != ''){
            $query->where('COD_CONTRATO', '=', $cliente);
        }
    }


    public function scopeSubCanal($query,$subcanal){
        if(trim($subcanal) != ''){
            $query->where('COD_CATEGORIA_SUB_CANAL', '=', $subcanal);
        }
    }

    public function scopeAdicionarvendedor($query,$name){


        if(trim($name) != ''){

            $query->Where(function($q){
                    $q->Where('COD_CATEGORIA_JEFE_VENTA','=', @Session::get('usuario')->fuerzaventa_id = null ? 'COD_CATEGORIA_JEFE_VENTA' : Session::get('usuario')->fuerzaventa_id);
                    $q->orWhere('COD_CATEGORIA_JEFE_VENTA', '=', 'JVE0000000000041');
                });
        }else{
            $query->Where('COD_CATEGORIA_JEFE_VENTA','=', @Session::get('usuario')->fuerzaventa_id = null ? 'COD_CATEGORIA_JEFE_VENTA' : Session::get('usuario')->fuerzaventa_id);

        }

    }



}
