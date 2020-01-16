<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente,App\STDTipoDocumento,App\WEBReglaProductoCliente;
use App\WEBPrecioProductoContrato,App\WEBPrecioProductoContratoHistorial;
use App\CMPContrato,App\ALMCentro,App\WEBAutorizacionNotaIngreso,App\WEBNotaIngreso;
use View;
use Session;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ContablilidadReporteController extends Controller
{


	public function actionAnticipoPrestamo($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		$combo_lista_centros 	= 	$this->funciones->combo_lista_centro();

		return View::make('contabilidad/reporte/anticipoprestamo',
						 [
						 	'idopcion' 					=> $idopcion,
						 	'combo_lista_centros' 		=> $combo_lista_centros,
							'inicio'					=> $this->inicio,
							'hoy'						=> $this->fin,
						 ]);

	}



	public function actionAnticipoPrestamoPDF($centro_id,$fechainicio,$fechafin)
	{



        //ACTUAL
		$listaliquidacioncompra 					=	WEBNotaIngreso::where('COD_EMPRESA','=',Session::get('empresas')->COD_EMPR)
														//->where('COD_CENTRO','=',$centro_id)
														//->where('FEC_AUTORIZACION','>=', $fechainicio)
	    												//->where('FEC_AUTORIZACION','<=', $fechafin)
														->where('SERIE','=','II201801')
	    												->orderBy('FEC_AUTORIZACION', 'asc')
														->get();




		$titulo 									= 	'Notas de ingreso';

		$pdf 										= 	PDF::loadView('contabilidad.pdf.anticipoprestamo', 
														[
															'listaliquidacioncompra' 	  	=> $listaliquidacioncompra,	
															'titulo' 	  					=> $titulo,			
														]);

		return $pdf->stream('download.pdf');
	}



/*
	public function actionAnticipoPrestamoPDF($centro_id,$fechainicio,$fechafin)
	{

		//HISTORICO
		$listaliquidacioncompra_h 					= 	WEBAutorizacionNotaIngreso::on('sqlsrv_h')
														->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
														->where('COD_CENTRO','=',$centro_id)
														->where('FEC_AUTORIZACION','>=', $fechainicio)
	    												->where('FEC_AUTORIZACION','<=', $fechafin)
	    												->orderBy('NOM_CENTRO', 'asc')
	    												->orderBy('COD_AUTORIZACION', 'asc')
														->get();


        //ACTUAL
		$listaliquidacioncompra 					=	WEBAutorizacionNotaIngreso::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
														->where('COD_CENTRO','=',$centro_id)
														->where('FEC_AUTORIZACION','>=', $fechainicio)
	    												->where('FEC_AUTORIZACION','<=', $fechafin)
	    												->orderBy('NOM_CENTRO', 'asc')
	    												->orderBy('COD_AUTORIZACION', 'asc')
														->get();

		$titulo 									= 	'Notas de ingreso';

		$pdf 										= 	PDF::loadView('contabilidad.pdf.anticipoprestamo', 
														[
															'listaliquidacioncompra' 	  	=> $listaliquidacioncompra,
															'listaliquidacioncompra_h' 	  	=> $listaliquidacioncompra_h,		
															'titulo' 	  					=> $titulo,			
														]);

		return $pdf->stream('download.pdf');
	}

*/



















}
