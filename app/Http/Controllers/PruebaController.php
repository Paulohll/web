<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\WEBRegla;
use Session;

class PruebaController extends Controller
{
    public function pruebas(Request $request)
    {

        $accion = 1;

        // lista reglas activas y que estan dentro de la fecha indicada
        if($accion = 1){



        	$cod_centro 		= 	'CEN0000000000002';
        	$cod_empresa 		= 	'IACHEM0000010394';
        	$tipo 				= 	'NEG';
        	$fecha_actual 	    = 	date('Y-m-d H:i');

			$lista_activas 		= 	WEBRegla::where('activo','=',1)
									->where('tiporegla','=',$tipo)
									->where('estado','=','PU')
									//->where('empresa_id','=',$cod_empresa)
	    							->where('centro_id','=',$cod_centro)
	    							->whereRaw('Convert(varchar(16), fechainicio, 120) <= ?', [$fecha_actual])
	    							->where(function ($query) use ($fecha_actual) {
									    $query->whereRaw('Convert(varchar(16), fechafin, 120) >= ?', [$fecha_actual])
									          ->orWhere('fechafin', '=', '1900-01-01 00:00:00.000');
									})
									->select('id', DB::raw("(nombre + ' ' + CASE WHEN tipodescuento = 'POR' THEN '%' WHEN tipodescuento = 'IMP' THEN 'S/.' END  + CAST(descuento AS varchar(100)) ) AS nombre"))
									->pluck('nombre','id')
									->toArray();

			print_r($lista_activas);

        }


      
    }


    public function indicadoresISL(Request $request)
    {
		return View::make('prueba/powerbi');
    }




}
