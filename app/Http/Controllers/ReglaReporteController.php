<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente,App\STDTipoDocumento,App\WEBReglaProductoCliente;
use App\WEBPrecioProductoContrato,App\WEBPrecioProductoContratoHistorial;
use View;
use Session;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ReglaReporteController extends Controller
{


	public function actionReglasXcliente($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		$comboclientes				= 	$this->funciones->combo_clientes_cuenta();
		$combotipoprecio_producto	= 	$this->funciones->combo_tipo_precio_productos_reglas();	
	
		return View::make('regla/reporte/reglaxcliente',
						 [
						 	'idopcion' 			=> $idopcion,
							'comboclientes' 			=> $comboclientes,
							'combotipoprecio_producto' 	=> $combotipoprecio_producto,						
							'inicio'			=> $this->inicio,
							'hoy'				=> $this->fin,
						 ]);

	}



	public function actionAjaxReglasxCliente(Request $request)
	{

		set_time_limit(0);
		$cuenta_id 						=  	$request['cuenta_id'];
		$tipoprecio_id 					=  	$request['tipoprecio_id'];		

		if($tipoprecio_id=='1'){
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_favotitos($cuenta_id);	
		}else{
			if($tipoprecio_id=='2'){
		    	$listadeproductos 				= 	$this->funciones->lista_productos_reglas($cuenta_id);	
			}else{
		    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();			
			}					
		}

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
											->orderBy('NOM_EMPR', 'asc')
											->get();

		$funcion 						= 	$this;


		return View::make('regla/reporte/ajax/listareglascliente',
						 [
							'listadeproductos'   	=> $listadeproductos,
							'listacliente'   		=> $listacliente,
						 	'funcion' 				=> $funcion,						 				 							 							 						 					 
						 ]);

	}




	public function actionReglasClienteExcel($idcuenta,$idtipoprecio)
	{
		set_time_limit(0);


		$cuenta_id 				=  	$idcuenta;
		$tipoprecio_id 			=  	$idtipoprecio;
		$nombretipoprecio		=   'TODO';

		$titulo 				=   'Reglas por cliente';

		if($tipoprecio_id=='1'){
			$nombretipoprecio				=   'CONTRATOS';
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_favotitos($cuenta_id);	
		}else{
			if($tipoprecio_id=='2'){
				$nombretipoprecio				=   'REGLAS';
		    	$listadeproductos 				= 	$this->funciones->lista_productos_reglas($cuenta_id);	
			}else{
		    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();			
			}					
		}

		$funcion 				= 	$this;	

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
											->orderBy('NOM_EMPR', 'asc')
											->get();

		$empresa 				= 	Session::get('empresas')->NOM_EMPR;
		$centro 				= 	Session::get('centros')->NOM_CENTRO;								


	    Excel::create($titulo.' ('.$nombretipoprecio.')', function($excel) use ($listadeproductos,$titulo,$listacliente,$funcion,$empresa,$centro) {

	        $excel->sheet('Reglas por cliente', function($sheet) use ($listadeproductos,$titulo,$listacliente,$funcion,$empresa,$centro) {

	            $sheet->loadView('regla/excel/listareglascliente')->with('listadeproductos',$listadeproductos)
	                                         		 ->with('titulo',$titulo)
	                                         		 ->with('listacliente',$listacliente)
	                                         		 ->with('empresa',$empresa)
	                                         		 ->with('centro',$centro)	                                         		 
	                                         		 ->with('funcion',$funcion);	                                         		 
	        });
	    })->export('xls');

	}


	public function actionReglaClientePDF($idcuenta,$idtipoprecio)
	{


		$cuenta_id 				=  	$idcuenta;
		$tipoprecio_id 			=  	$idtipoprecio;
		$nombretipoprecio		=   'TODO';

		$titulo 				=   'Reglas por cliente';

		if($tipoprecio_id=='1'){
			$nombretipoprecio				=   'CONTRATOS';
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_favotitos($cuenta_id);	
		}else{
			if($tipoprecio_id=='2'){
				$nombretipoprecio				=   'REGLAS';
		    	$listadeproductos 				= 	$this->funciones->lista_productos_reglas($cuenta_id);	
			}else{
		    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();			
			}					
		}

		$funcion 				= 	$this;	

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
											->orderBy('NOM_EMPR', 'asc')
											->get();

		$empresa 				= 	Session::get('empresas')->NOM_EMPR;
		$centro 				= 	Session::get('centros')->NOM_CENTRO;

		$fechaactual 			=   $this->fechaactualinput;

		$pdf 					= 	PDF::loadView('regla.pdf.listareglascliente', 
												[
													'listadeproductos' 	  => $listadeproductos,
													'titulo' 		  	  => $titulo,
													'listacliente' 		  => $listacliente,
													'empresa' 		  	  => $empresa,											
													'centro' 		  	  => $centro,
													'funcion' 		  	  => $funcion,
													'fechaactual' 		  => $fechaactual,	
												]);

		return $pdf->stream('download.pdf');
	}



}
