<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Biblioteca\Funcion;
use DateTime;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $funciones;
	public $inicio;
	public $fin;
	public $prefijomaestro;
	public $fechaActual;
	public $fechavacia;

	public function __construct()
	{
	    $this->funciones = new Funcion();
		$fecha = new DateTime();
		$fecha->modify('first day of this month');

		$fechames = new DateTime();
		$fechames->modify('next month');

		//fecha actual 30 dias
		$fechatreinta = date('Y-m-j');
		$nuevafecha = strtotime ( '-90 day' , strtotime($fechatreinta));
		$nuevafecha = date ('Y-m-j' , $nuevafecha);


		$this->fecha_menos_treinta_dias = date_format(date_create($nuevafecha), 'd-m-Y');

		$this->inicio 					= date_format(date_create($fecha->format('Y-m-d')), 'd-m-Y');
		$this->fin 						= date_format(date_create(date('Y-m-d')), 'd-m-Y');
		$this->messiguiente 			= date_format(date_create($fechames->format('Y-m-d')), 'd-m-Y');
		$this->fechaactual 				= date('d-m-Y H:i:s');
		$this->fechaactualinput 		= date('d-m-Y H:i');
		$this->prefijomaestro			= $this->funciones->prefijomaestra();
		$this->fechavacia				= "1900-01-01 00:00:00.000";

	}




}
