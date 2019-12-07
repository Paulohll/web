<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente,App\STDTipoDocumento,App\WEBReglaProductoCliente;
use App\WEBPrecioProductoContrato,App\WEBPrecioProductoContratoHistorial;
use App\CMPContrato;
use View;
use Session;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ProductoReporteController extends Controller
{



	public function actionPrecioCanalMayoristaPDF($fechadia)
	{


		$nombretipoprecio							=   $fechadia;
		$titulo 									=   'Precios deL Canal mayorista';

	    $listadeproductos 							= 	$this->funciones->lista_productos_precio();			


		$lista_subcanales_canales_responsable		=	CMPContrato::whereIn('CMP.CONTRATO.COD_CATEGORIA_SUB_CANAL',['SCV0000000000004' ,'SCV0000000000020','SCV0000000000005'])
														->select('COD_CATEGORIA_SUB_CANAL','TXT_CATEGORIA_SUB_CANAL')
														->groupBy('TXT_CATEGORIA_SUB_CANAL')
														->groupBy('COD_CATEGORIA_SUB_CANAL')
														->get();


		$funcion 									= 	$this;
		$empresa 									= 	Session::get('empresas')->NOM_EMPR;
		$centro 									= 	Session::get('centros')->NOM_CENTRO;	
		$fechaactual 								=   $fechadia;



		$pdf 					= 	PDF::loadView('catalogo.pdf.listapreciocanalmayorista', 
												[
													'listadeproductos' 	  => $listadeproductos,
													'titulo' 		  	  => $titulo,
													'lista_subcanales_canales_responsable' 		  => $lista_subcanales_canales_responsable,
													'empresa' 		  	  => $empresa,											
													'centro' 		  	  => $centro,
													'funcion' 		  	  => $funcion,
													'fechafin' 		  => $fechaactual,									
												]);

		return $pdf->stream('download.pdf');
	}




	public function actionPrecioCanalMayoristaExcel($fechadia)
	{
		set_time_limit(0);


		$nombretipoprecio							=   $fechadia;
		$titulo 									=   'Precios deL Canal mayorista';


	    $listadeproductos 							= 	$this->funciones->lista_productos_precio();			


		$lista_subcanales_canales_responsable		=	CMPContrato::whereIn('CMP.CONTRATO.COD_CATEGORIA_SUB_CANAL',['SCV0000000000004' ,'SCV0000000000020','SCV0000000000005'])
														->select('COD_CATEGORIA_SUB_CANAL','TXT_CATEGORIA_SUB_CANAL')
														->groupBy('TXT_CATEGORIA_SUB_CANAL')
														->groupBy('COD_CATEGORIA_SUB_CANAL')
														->get();


		$funcion 									= 	$this;
		$empresa 									= 	Session::get('empresas')->NOM_EMPR;
		$centro 									= 	Session::get('centros')->NOM_CENTRO;	
		$fechaactual 								=   $fechadia;




	    Excel::create($titulo.' ('.$nombretipoprecio.')', function($excel) use ($listadeproductos,$titulo,$lista_subcanales_canales_responsable,$funcion,$empresa,$centro,$fechaactual) {

	        $excel->sheet('Precios Productos', function($sheet) use ($listadeproductos,$titulo,$lista_subcanales_canales_responsable,$funcion,$empresa,$centro,$fechaactual) {

	            $sheet->loadView('catalogo/excel/listapreciocanalmayorista')->with('listadeproductos',$listadeproductos)
	                                         		 ->with('titulo',$titulo)
	                                         		 ->with('lista_subcanales_canales_responsable',$lista_subcanales_canales_responsable)
	                                         		 ->with('empresa',$empresa)
	                                         		 ->with('centro',$centro)	                                         		 
	                                         		 ->with('funcion',$funcion)
	                                         		 ->with('fechafin',$fechaactual);                                        		 
	        });
	    })->export('xls');

	}



	public function actionAjaxPrecioCanalMayorista(Request $request)
	{

		set_time_limit(0);
		$cuenta_id 									=  	$request['cuenta_id'];
		$fechafin 									=  	$request['fechafin'];	

	    $listadeproductos 							= 	$this->funciones->lista_productos_precio();			


		$lista_subcanales_canales_responsable		=	CMPContrato::whereIn('CMP.CONTRATO.COD_CATEGORIA_SUB_CANAL',['SCV0000000000004' ,'SCV0000000000020','SCV0000000000005'])
														->select('COD_CATEGORIA_SUB_CANAL','TXT_CATEGORIA_SUB_CANAL')
														->groupBy('TXT_CATEGORIA_SUB_CANAL')
														->groupBy('COD_CATEGORIA_SUB_CANAL')
														->get();


		$funcion 									= 	$this;

		return View::make('catalogo/reporte/ajax/listapreciocanalmayorista',
						 [
							'listadeproductos'   							=> $listadeproductos,
							'lista_subcanales_canales_responsable'   		=> $lista_subcanales_canales_responsable,
						 	'funcion' 										=> $funcion,
						 	'fechafin' 										=> $fechafin,				 						 					 
						 ]);

	}




	public function actionPrecioCanalMayorista($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
		return View::make('catalogo/reporte/preciocanalmayorista',
						 [
						 	'idopcion' 					=> $idopcion,
							'inicio'					=> $this->inicio,
							'hoy'						=> $this->fin,
						 ]);

	}








	public function actionEvolucionPrecioProductoXcliente($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		$comboclientes				= 	$this->funciones->combo_clientes_cuenta();
		$combotipoprecio_producto	= 	$this->funciones->combo_tipo_precio_productos();		
	
		return View::make('catalogo/reporte/evolucionprecioproductoxcliente',
						 [
						 	'idopcion' 					=> $idopcion,
							'comboclientes' 			=> $comboclientes,
							'combotipoprecio_producto' 	=> $combotipoprecio_producto,
							'inicio'					=> $this->inicio,
							'hoy'						=> $this->fin,
						 ]);

	}


	public function actionAjaxEvolucionProductosxCliente(Request $request)
	{

		set_time_limit(0);
		$cuenta_id 						=  	$request['cuenta_id'];
		$fechafin 						=  	$request['fechafin'];	

	    $listadeproductos 				= 	$this->funciones->lista_productos_precio();			

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
											->orderBy('NOM_EMPR', 'asc')
											->get();

		$funcion 						= 	$this;


		return View::make('catalogo/reporte/ajax/listaevolucionprecioproducto',
						 [
							'listadeproductos'   	=> $listadeproductos,
							'listacliente'   		=> $listacliente,
						 	'funcion' 				=> $funcion,
						 	'fechafin' 				=> $fechafin,				 						 					 
						 ]);

	}


	public function actionEvolucionPrecioProductoClientePDF($idcuenta,$fechadia)
	{


		$nombretipoprecio				=   'TODO';
		$titulo 						=   'Evolucion precios de los productos';
	    $listadeproductos 				= 	$this->funciones->lista_productos_precio();			

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$idcuenta)
											->orderBy('NOM_EMPR', 'asc')
											->get();
		$funcion 						= 	$this;
		$empresa 						= 	Session::get('empresas')->NOM_EMPR;
		$centro 						= 	Session::get('centros')->NOM_CENTRO;	
		$fechaactual 					=   $fechadia;



		$pdf 					= 	PDF::loadView('catalogo.pdf.listaevolucionprecioproducto', 
												[
													'listadeproductos' 	  => $listadeproductos,
													'titulo' 		  	  => $titulo,
													'listacliente' 		  => $listacliente,
													'empresa' 		  	  => $empresa,											
													'centro' 		  	  => $centro,
													'funcion' 		  	  => $funcion,
													'fechafin' 		  => $fechaactual,									
												]);

		return $pdf->stream('download.pdf');
	}



	public function actionEvolucionPrecioProductoClienteExcel($idcuenta,$fechadia)
	{
		set_time_limit(0);


		$nombretipoprecio				=   'TODO';
		$titulo 						=   'Evolucion precios de los productos';
	    $listadeproductos 				= 	$this->funciones->lista_productos_precio();			

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$idcuenta)
											->orderBy('NOM_EMPR', 'asc')
											->get();
		$funcion 						= 	$this;
		$empresa 						= 	Session::get('empresas')->NOM_EMPR;
		$centro 						= 	Session::get('centros')->NOM_CENTRO;	
		$fechaactual 					=   $fechadia;

		$funcion 						= 	$this;	


	    Excel::create($titulo.' ('.$nombretipoprecio.')', function($excel) use ($listadeproductos,$titulo,$listacliente,$funcion,$empresa,$centro,$fechaactual) {

	        $excel->sheet('Precios Productos', function($sheet) use ($listadeproductos,$titulo,$listacliente,$funcion,$empresa,$centro,$fechaactual) {

	            $sheet->loadView('catalogo/excel/listaevolucionprecioproducto')->with('listadeproductos',$listadeproductos)
	                                         		 ->with('titulo',$titulo)
	                                         		 ->with('listacliente',$listacliente)
	                                         		 ->with('empresa',$empresa)
	                                         		 ->with('centro',$centro)	                                         		 
	                                         		 ->with('funcion',$funcion)
	                                         		 ->with('fechafin',$fechaactual);                                        		 
	        });
	    })->export('xls');

	}





	public function actionPrecioProductoXcliente($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		$comboclientes				= 	$this->funciones->combo_clientes_cuenta();
		$combotipoprecio_producto	= 	$this->funciones->combo_tipo_precio_productos();		
	
		return View::make('catalogo/reporte/precioproductoxcliente',
						 [
						 	'idopcion' 					=> $idopcion,
							'comboclientes' 			=> $comboclientes,
							'combotipoprecio_producto' 	=> $combotipoprecio_producto,
							'inicio'					=> $this->inicio,
							'hoy'						=> $this->fin,
						 ]);

	}


	public function actionAjaxProductosxCliente(Request $request)
	{

		set_time_limit(0);
		$cuenta_id 						=  	$request['cuenta_id'];
		$tipoprecio_id 					=  	$request['tipoprecio_id'];		

		if($tipoprecio_id=='1'){

	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_favotitos($cuenta_id);	


		}else{
	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();			
		}

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
											->orderBy('NOM_EMPR', 'asc')
											->get();




		$funcion 						= 	$this;


		return View::make('catalogo/reporte/ajax/listaprecioproducto',
						 [
							'listadeproductos'   	=> $listadeproductos,
							'listacliente'   		=> $listacliente,
						 	'funcion' 				=> $funcion,						 				 							 							 						 					 
						 ]);

	}



	public function actionPrecioProductoClienteExcel($idcuenta,$idtipoprecio)
	{
		set_time_limit(0);


		$cuenta_id 				=  	$idcuenta;
		$tipoprecio_id 			=  	$idtipoprecio;
		$nombretipoprecio		=   'TODO';

		if($tipoprecio_id == '1'){
			$nombretipoprecio		=   'CONTRATOS';
		}

		$titulo 				=   'Precios de los Productos';
		if($tipoprecio_id=='1'){
	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_favotitos($cuenta_id);	

		}else{
	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();			
		}


		$funcion 				= 	$this;	

		// lista de clientes
		$listacliente 					= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
											->orderBy('NOM_EMPR', 'asc')
											->get();

		$empresa 				= 	Session::get('empresas')->NOM_EMPR;
		$centro 				= 	Session::get('centros')->NOM_CENTRO;								


	    Excel::create($titulo.' ('.$nombretipoprecio.')', function($excel) use ($listadeproductos,$titulo,$listacliente,$funcion,$empresa,$centro) {

	        $excel->sheet('Precios Productos', function($sheet) use ($listadeproductos,$titulo,$listacliente,$funcion,$empresa,$centro) {

	            $sheet->loadView('catalogo/excel/listaprecioproducto')->with('listadeproductos',$listadeproductos)
	                                         		 ->with('titulo',$titulo)
	                                         		 ->with('listacliente',$listacliente)
	                                         		 ->with('empresa',$empresa)
	                                         		 ->with('centro',$centro)	                                         		 
	                                         		 ->with('funcion',$funcion);	                                         		 
	        });
	    })->export('xls');

	}




	public function actionPrecioProductoClientePDF($idcuenta,$idtipoprecio)
	{

		$cuenta_id 				=  	$idcuenta;
		$tipoprecio_id 			=  	$idtipoprecio;
		$nombretipoprecio		=   'TODO';

		if($tipoprecio_id == '1'){
			$nombretipoprecio		=   'CONTRATOS';
		}

		$titulo 				=   'Precios de los Productos';
		if($tipoprecio_id=='1'){
	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_favotitos($cuenta_id);	

		}else{
	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();			
		}


		$funcion 				= 	$this;	

		// lista de clientes
		$listacliente 			= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)
									->orderBy('NOM_EMPR', 'asc')
									->get();

		$empresa 				= 	Session::get('empresas')->NOM_EMPR;
		$centro 				= 	Session::get('centros')->NOM_CENTRO;	
		$fechaactual 			=   $this->fechaactualinput;



		$pdf 					= 	PDF::loadView('catalogo.pdf.listaprecioproducto', 
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
