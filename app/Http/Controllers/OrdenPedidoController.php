<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente,App\STDTipoDocumento,App\WEBReglaProductoCliente,App\WEBPedido;
use App\WEBDetallePedido,App\CMPCategoria,App\WEBReglaCreditoCliente,App\STDEmpresa,App\WEBPrecioProducto,App\WEBMaestro,App\WEBPrecioProductoContrato,App\STDEmpresaDireccion;
use View;
use Session;
use App\Biblioteca\Osiris;
use App\Biblioteca\Funcion;
use PDO;
use Mail;
use PDF;
  
class OrdenPedidoController extends Controller
{

	public function actionAjaxDeudaCliente(Request $request)
	{
		$pedido_id 					= 	$request['pedido_id'];
		$pedido_id 					= 	$this->funciones->desencriptar_id('1CIX-'.$pedido_id,8);
		$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();
		$lista_deuda_cliente		= 	$this->funciones->lista_saldo_cuenta_documento($this->fechaactual,'TCO0000000000068',$pedido->cliente_id,'CON');
		$funcion 					= 	$this;			
		$limite_credito				= 	$this->funciones->data_regla_limite_credito($pedido->cliente_id);


		return View::make('pedido/ajax/modaldeudacliente',
						 [
							 'pedido_id'   				=> $pedido_id,
							 'pedido'   				=> $pedido,
							 'lista_deuda_cliente'   	=> $lista_deuda_cliente,
							 'funcion'   				=> $funcion,
							 'limite_credito'   		=> $limite_credito,
						 ]);
	}

	public function actionAjaxDetallePedidoRechazar(Request $request)
	{

		$detalle_pedido_id 					= 	$request['detalle_pedido_id'];
		$detallepedido 						=   WEBDetallePedido::where('id','=',$detalle_pedido_id)->first();
		$mensaje 							=  	'Estado del producto ('.$detallepedido->producto->NOM_PRODUCTO.') modificado con exito';
		$estado_pedido 						=   'EPP0000000000005';

		$response 							= 	$this->funciones->cambiar_estado_detalle_pedido($detalle_pedido_id,$mensaje,$estado_pedido);
		if($response[0]['error']){echo json_encode($response); exit();}

		echo json_encode($response);

	}


	public function actionAjaxGuardarPrecioProductoPedido(Request $request)
	{

		$precio 							= 	$request['precio'];
		$detallepedido_id 					= 	$request['detallepedido_id'];
		$pedido_id 							= 	$request['pedido_id'];
		$detallepedido 						=   WEBDetallePedido::where('id','=',$detallepedido_id)->first();

		$mensaje 							=  	'Precio del producto ('.$detallepedido->producto->NOM_PRODUCTO.') modificado con exito';

		$response 							= 	$this->funciones->el_pedido_estado_generado($pedido_id,$mensaje);
		if($response[0]['error']){echo json_encode($response); exit();}


		//reclaculo de importe,igv,subtotal detallepedido	

		$importe 							=   $detallepedido->cantidad * (float)$precio;
	    $detallepedido->precio 				= 	(float)$precio;
	    $detallepedido->igv 				= 	$this->funciones->calculo_igv($importe);
	    $detallepedido->subtotal 			= 	$this->funciones->calculo_subtotal($importe);
	    $detallepedido->total 				= 	$importe;
		$detallepedido->fecha_mod 	 		=   $this->fechaactual;
		$detallepedido->usuario_mod 		=   Session::get('usuario')->id;
		$detallepedido->save();
		//reclaculo de importe,igv,subtotal pedido
		$this->funciones->calculo_totales_pedido($pedido_id);


		echo json_encode($response);


	}

	public function actionAjaxGuardarCantidadProductoPedido(Request $request)
	{

		$cantidad 							= 	$request['cantidad'];
		$detallepedido_id 					= 	$request['detallepedido_id'];
		$pedido_id 							= 	$request['pedido_id'];
		$detallepedido 						=   WEBDetallePedido::where('id','=',$detallepedido_id)->first();

		$mensaje 							=  	'Cantidad producto ('.$detallepedido->producto->NOM_PRODUCTO.') modificado con exito';

		$response 							= 	$this->funciones->el_pedido_estado_generado($pedido_id,$mensaje);
		if($response[0]['error']){echo json_encode($response); exit();}


		//reclaculo de importe,igv,subtotal detallepedido	

		$importe 							=   $detallepedido->precio * (float)$cantidad;
	    $detallepedido->cantidad 			= 	$cantidad;
	    $detallepedido->igv 				= 	$this->funciones->calculo_igv($importe);
	    $detallepedido->subtotal 			= 	$this->funciones->calculo_subtotal($importe);
	    $detallepedido->total 				= 	$importe;
		$detallepedido->fecha_mod 	 		=   $this->fechaactual;
		$detallepedido->usuario_mod 		=   Session::get('usuario')->id;
		$detallepedido->save();
		//reclaculo de importe,igv,subtotal pedido
		$this->funciones->calculo_totales_pedido($pedido_id);


		echo json_encode($response);


	}



	public function actionAjaxDetallePedidoMobil(Request $request)
	{
		$pedido_id 					= 	$request['pedido_id'];
		$pedido_id 					= 	$this->funciones->desencriptar_id('1CIX-'.$pedido_id,8);
		$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();
		$funcion 					= 	$this;			


		return View::make('pedido/ajax/modaldetallepedidomobil',
						 [
							 'pedido_id'   	=> $pedido_id,
							 'pedido'   	=> $pedido,
							 'funcion'   	=> $funcion,
						 ]);

	}


	public function actionNoAutorizarPedido($idopcion,Request $request)
	{

		if($_POST)
		{

			$msjarray  			= array();
			$respuesta 			= json_decode($request['pedidorechazar'], true);
			$finicio 			= $request['fechainiciorechazar'];
			$fechafin 			= $request['fechafinrechazar'];
	        $conts   			= 0;
	        $contw				= 0;
			$contd				= 0;
		

			foreach($respuesta as $obj){

				$pedido_id 					= 	$this->funciones->desencriptar_id('1CIX-'.$obj['id'],8);
				$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();


			    if($pedido->estado_id == 'EPP0000000000002'){ 

				    $pedido->estado_id 				 	= 	'EPP0000000000005';
					$pedido->fecha_autorizacion 	 	=   $this->fechaactual;
					$pedido->usuario_autorizacion 		=   Session::get('usuario')->id;
   					$pedido->save();

					WEBDetallePedido::where('pedido_id','=',$pedido_id)
					->where('activo','=',1)
					->update(['estado_id' => 'EPP0000000000005', 'fecha_mod' =>  $this->fechaactual,'usuario_mod' => Session::get('usuario')->id]);



			    	$msjarray[] 						= 	array(	"data_0" => $pedido->codigo, 
			    											"data_1" => 'pedido rechazado', 
			    											"tipo" => 'S');
			    	$conts 								= 	$conts + 1;

			    }else{


					/**** ERROR DE PROGRMACION O SINTAXIS ****/
					$msjarray[] = array("data_0" => $pedido->codigo, 
										"data_1" => 'este pedido esta autorizado', 
										"tipo" => 'D');
					$contd 		= 	$contd + 1;


			    }

			}


			/************** MENSAJES DEL DETALLE PEDIDO  ******************/
	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'pedidos rechazado', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => $contw, 
	    						"data_1" => 'pedidos', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => $contd, 
	    						"data_1" => 'pedidos errados', 
	    						"tipo" => 'TD');

			$msjarray[] = array("data_0" => $finicio, 
								"data_1" => $fechafin, 
								"tipo" => 'FE');

			$msjjson = json_encode($msjarray);


			return Redirect::to('/gestion-de-orden-de-pedido-autorizacion/'.$idopcion)->with('xmlmsj', $msjjson);

		
		}
	}
	public function actionAutorizarPedido($idopcion,Request $request)
	{

		if($_POST)
		{

			$msjarray  			= array();
			$respuesta 			= json_decode($request['pedido'], true);
			$finicio 			= $request['fechainicio'];
			$fechafin 			= $request['fechafin'];
	        $conts   			= 0;
	        $contw				= 0;
			$contd				= 0;
		

			foreach($respuesta as $obj){

				$pedido_id 					= 	$this->funciones->desencriptar_id('1CIX-'.$obj['id'],8);
				$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();


			    if($pedido->estado_id == 'EPP0000000000002'){ 

				    $pedido->estado_id 				 		= 	'EPP0000000000003';
					$pedido->fecha_autorizacion 	 		=   $this->fechaactual;
					$pedido->ind_notificacion_autorizacion 	=   0;
					$pedido->usuario_autorizacion 			=   Session::get('usuario')->id;
					$np=$pedido;
   					$pedido->save();

					WEBDetallePedido::where('pedido_id','=',$pedido_id)
					->where('activo','=',1)
					->whereNull('estado_id')
					->orWhere('estado_id','=', '')
					->update(['estado_id' => 'EPP0000000000003', 'fecha_mod' =>  $this->fechaactual,'usuario_mod' => Session::get('usuario')->id]);
					
					


			    	$msjarray[] 			= 	array(	"data_0" => $pedido->codigo, 
			    									"data_1" => 'pedido autorizado', 
			    									"tipo" => 'S');
					$conts 					= 	$conts + 1;
					
					$codigo 				= 	$pedido->codigo;

			    }else{


					/**** ERROR DE PROGRMACION O SINTAXIS ****/
					$msjarray[] = array("data_0" => $pedido->codigo, 
										"data_1" => 'este pedido esta autorizado', 
										"tipo" => 'D');
					$contd 		= 	$contd + 1;


			    }

			}


			/************** MENSAJES DEL DETALLE PEDIDO  ******************/
	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'pedidos autorizados', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => $contw, 
	    						"data_1" => 'pedidos', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => $contd, 
	    						"data_1" => 'pedidos errados', 
	    						"tipo" => 'TD');

			$msjarray[] = array("data_0" => $finicio, 
								"data_1" => $fechafin, 
								"tipo" => 'FE');

			$msjjson = json_encode($msjarray);


			return Redirect::to('/gestion-de-orden-de-pedido-autorizacion/'.$idopcion)->with('xmlmsj', $msjjson);

		
		}
	}
	public function actionListarTomaPedidoAutorizacion($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/


	    if (Session::get('xmlmsj')){

	    	$obj 				= 	json_decode(Session::get('xmlmsj'));
	    	$pfi 				= 	array_search('FE', array_column($obj, 'tipo'));
	    	$pff 				= 	array_search('FE', array_column($obj, 'tipo'));
            $fechainicio 		= 	$obj[$pfi]->data_0;
            $fechafin 			= 	$obj[$pff]->data_1;

	    }else{

		    $fechainicio  		= 	$this->inicio;
		    $fechafin  			= 	$this->fin;

	    }

	    $listapedidos			= 	WEBPedido::where('activo','=',1)
		    						->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
		    						->whereIn('estado_id', ['EPP0000000000002'])
			    					->where('fecha_venta','>=', $fechainicio)
			    					->where('fecha_venta','<=', $fechafin)
									//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)
		    						->orderBy('fecha_venta', 'desc')
		    						->get();

		$funcion 					= 	$this;

		return View::make('pedido/listatomapedidoautorizacion',
						 [
						 	'idopcion' 		=> $idopcion,
						 	'listapedidos' 	=> $listapedidos,
						 	'fechainicio' 	=> $fechainicio,
						 	'fechafin' 		=> $fechafin,
						 	'funcion' 		=> $funcion,
						 ]);
	}
	public function actionAjaxListarTomaPedidoAutorizacion(Request $request)
	{

		$finicio 		=  date_format(date_create($request['finicio']), 'd-m-Y');
		$ffin 			=  date_format(date_create($request['ffin']), 'd-m-Y');

	    $listapedidos	= 	WEBPedido::where('activo','=',1)
	    					->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
		    				->whereIn('estado_id', ['EPP0000000000002'])
							//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
							->where('centro_id','=',Session::get('centros')->COD_CENTRO)
		    				->where('fecha_venta','>=', $finicio)
		    				->where('fecha_venta','<=', $ffin)
	    					->orderBy('fecha_venta', 'desc')
	    					->get();
	    			
		$funcion 		= 	$this;

		return View::make('pedido/ajax/listatomapedidoautorizacion',
						 [
							 'listapedidos'   => $listapedidos,
							 'ajax'   		  => true,
							 'funcion'   	  => $funcion,
						 ]);
	}
	public function actionAjaxDetallePedidoAutorizacion(Request $request)
	{
		$pedido_id 					= 	$request['pedido_id'];
		$pedido_id 					= 	$this->funciones->desencriptar_id('1CIX-'.$pedido_id,8);
		$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();
		$funcion 					= 	$this;			


		return View::make('pedido/ajax/modaldetallepedidoautorizacion',
						 [
							 'pedido_id'   	=> $pedido_id,
							 'pedido'   	=> $pedido,
							 'funcion'   	=> $funcion,
						 ]);
	}
	public function actionEnviarOsirisRechazar($idopcion,Request $request)
	{

		if($_POST)
		{

			$msjarray  			= array();
			$respuesta 			= json_decode($request['pedidorechazar'], true);
			$finicio 			= $request['fechainiciorechazar'];
			$fechafin 			= $request['fechafinrechazar'];
	        $conts   			= 0;
	        $contw				= 0;
			$contd				= 0;
		

			foreach($respuesta as $obj){

				$pedido_id 						= 	$this->funciones->desencriptar_id('1CIX-'.$obj['id'],8);
				$lista_array_detalle_pedido		= 	json_decode($obj['detalle'], true);
				$pedido 						=   WEBPedido::where('id','=',$pedido_id)->first();

				//filtrar solo check
				$eliminar 						= 	array("checked" => "checked");
				$lista_array_detalle_pedido 	= 	array_filter($lista_array_detalle_pedido, function($lista_array_detalle_pedido) use ($eliminar){
				    return in_array($lista_array_detalle_pedido['checked'], $eliminar);
				});
				$lista_array_detalle_pedido 	= array_values($lista_array_detalle_pedido);

				// guardar id de detalle pedido
				$array_detalle_pedido_id 		= 	array();
				foreach($lista_array_detalle_pedido as $key => $obj_det){
					$array_detalle_pedido_id[$key] =  $obj_det['detalle_pedido_id'];
				}


			    if($pedido->estado_id == 'EPP0000000000003'){ 

					WEBDetallePedido::whereIn('id',$array_detalle_pedido_id)
					->update(['estado_id' => 'EPP0000000000005', 'fecha_mod' =>  $this->fechaactual,'usuario_mod' => Session::get('usuario')->id]);

					$this->funciones->estado_pedido_ejecutado($pedido);
				



			    	$msjarray[] 			= 	array(	"data_0" => $pedido->codigo, 
			    									"data_1" => 'pedido rechazado', 
			    									"tipo" => 'S');
			    	$conts 					= 	$conts + 1;

			    }else{


					/**** ERROR DE PROGRMACION O SINTAXIS ****/
					$msjarray[] = array("data_0" => $pedido->codigo, 
										"data_1" => 'este pedido esta ejecutado', 
										"tipo" => 'D');
					$contd 		= 	$contd + 1;


			    }

			}


			/************** MENSAJES DEL DETALLE PEDIDO  ******************/
	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'pedidos rechazados', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => $contw, 
	    						"data_1" => 'pedidos', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => $contd, 
	    						"data_1" => 'pedidos errados', 
	    						"tipo" => 'TD');

			$msjarray[] = array("data_0" => $finicio, 
								"data_1" => $fechafin, 
								"tipo" => 'FE');

			$msjjson = json_encode($msjarray);

			return Redirect::to('/gestion-de-orden-de-pedido/'.$idopcion)->with('xmlmsj', $msjjson);

		
		}
	}
	public function actionEnviarOsiris($idopcion,Request $request)
	{

		if($_POST)
		{

			$msjarray  			= array();
			$respuesta 			= json_decode($request['pedido'], true);
			$finicio 			= $request['fechainicio'];
			$fechafin 			= $request['fechafin'];
	        $conts   			= 0;
	        $contw				= 0;
			$contd				= 0;


			foreach($respuesta as $obj){

				$pedido_id 						= 	$this->funciones->desencriptar_id('1CIX-'.$obj['id'],8);
				$lista_array_detalle_pedido		= 	json_decode($obj['detalle'], true);
				$pedido 						=   WEBPedido::where('id','=',$pedido_id)->first();
				$osiris 						= 	new Osiris();

				//filtrar solo check
				$eliminar 						= 	array("checked" => "checked");
				$lista_array_detalle_pedido 	= 	array_filter($lista_array_detalle_pedido, function($lista_array_detalle_pedido) use ($eliminar){
				    return in_array($lista_array_detalle_pedido['checked'], $eliminar);
				});
				$lista_array_detalle_pedido 	= array_values($lista_array_detalle_pedido);

				//agrupar las empresas que van a guardarse
				$group_detalle_pedido 			=  $this->funciones->grouparray($lista_array_detalle_pedido,'empresa_id');

				foreach($group_detalle_pedido as $key => $obj_empresa){

					// array de id_detalle_pedido por empresa
					$empresa_id 					=   $obj_empresa['empresa_id'];
					$empresa_filter 				= 	array("empresa_id" => $obj_empresa['empresa_id']);
					$lista_array_empresa 			= 	array_filter($lista_array_detalle_pedido, function($lista_array_detalle_pedido) use ($empresa_filter){
					    return in_array($lista_array_detalle_pedido['empresa_id'], $empresa_filter);
					});
					$lista_array_empresa 			= array_values($lista_array_empresa);

					// guardar id de detalle pedido
					$array_detalle_pedido_id 		= 	array();
					foreach($lista_array_empresa as $key => $obj_det){
						$array_detalle_pedido_id[$key] =  $obj_det['detalle_pedido_id'];
					}


					$pedidoagrupado 				=   WEBDetallePedido::where('pedido_id','=',$pedido_id)
														->whereIn('id',$array_detalle_pedido_id)
														->select(DB::raw("sum(total) as total"))
														->first();

					$respuesta 						=  	$osiris->guardar_orden_pedido_por_detalle($pedido,$pedidoagrupado,$array_detalle_pedido_id,$empresa_id);


				}


			    if($respuesta){ 

			    	$this->funciones->estado_pedido_ejecutado($pedido);
			    	$msjarray[] 			= 	array(	"data_0" => $pedido->codigo, 
			    									"data_1" => 'aceptado a osiris', 
			    									"tipo" => 'S');
			    	$conts 					= 	$conts + 1;						
					$codigo 				= 	$pedido->codigo;

		
			    }else{


					/**** ERROR DE PROGRMACION O SINTAXIS ****/
					$msjarray[] = array("data_0" => $pedido->codigo, 
										"data_1" => $osiris->msjerror, 
										"tipo" => 'D');
					$contd 		= 	$contd + 1;


			    }

			}


			/************** MENSAJES DEL DETALLE PEDIDO  ******************/
	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'pedidos aceptados', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => $contw, 
	    						"data_1" => 'pedidos rechazados', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => $contd, 
	    						"data_1" => 'pedidos errados', 
	    						"tipo" => 'TD');

			$msjarray[] = array("data_0" => $finicio, 
								"data_1" => $fechafin, 
								"tipo" => 'FE');

			$msjjson = json_encode($msjarray);

			return Redirect::to('/gestion-de-orden-de-pedido/'.$idopcion)->with('xmlmsj', $msjjson);

		
		}
	}
	public function actionListarTomaPedido($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/


	    if (Session::get('xmlmsj')){

	    	$obj 				= 	json_decode(Session::get('xmlmsj'));
	    	$pfi 				= 	array_search('FE', array_column($obj, 'tipo'));
	    	$pff 				= 	array_search('FE', array_column($obj, 'tipo'));
            $fechainicio 		= 	$obj[$pfi]->data_0;
            $fechafin 			= 	$obj[$pff]->data_1;

	    }else{

		    $fechainicio  		= 	$this->fecha_menos_quince;
		    $fechafin  			= 	$this->fin;

	    }


	    $listapedidos			= 	WEBPedido::where('activo','=',1)
		    						->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
		    						->whereIn('estado_id', ['EPP0000000000003'])
			    					->where('fecha_venta','>=', $fechainicio)
			    					->where('fecha_venta','<=', $fechafin)
									//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)
		    						->orderBy('fecha_venta', 'desc')
		    						->get();


		$combo_estados  		= 	array('EPP0000000000003' => "AUTORIZADO",
									'EPP0000000000004' => "EJECUTADO",
									'TODOS' => "TODOS");


		$funcion 					= 	$this;

		return View::make('pedido/listatomapedido',
						 [
						 	'idopcion' 		=> $idopcion,
						 	'listapedidos' 	=> $listapedidos,
						 	'fechainicio' 	=> $fechainicio,
						 	'fechafin' 		=> $fechafin,
						 	'funcion' 		=> $funcion,
						 	'combo_estados' => $combo_estados,
						 ]);
	}
	public function actionAjaxListarTomaPedido(Request $request)
	{

		$finicio 		=  date_format(date_create($request['finicio']), 'd-m-Y');
		$ffin 			=  date_format(date_create($request['ffin']), 'd-m-Y');
		$estado_id 		=  $request['estado_id'];


		if($estado_id == 'TODOS'){

		    $listapedidos	= 	WEBPedido::where('activo','=',1)
					->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
					->whereIn('estado_id', ['EPP0000000000003','EPP0000000000004'])
					//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
    				->where('fecha_venta','>=', $finicio)
    				->where('fecha_venta','<=', $ffin)
					->orderBy('fecha_venta', 'desc')
					->get();
		}else{
		    $listapedidos	= 	WEBPedido::where('activo','=',1)
					->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
					->whereIn('estado_id', [$estado_id])
					//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
    				->where('fecha_venta','>=', $finicio)
    				->where('fecha_venta','<=', $ffin)
					->orderBy('fecha_venta', 'desc')
					->get();			
		}


	    			
		$funcion 		= 	$this;

		return View::make('pedido/ajax/listatomapedido',
						 [
							 'listapedidos'   => $listapedidos,
							 'ajax'   		  => true,
							 'funcion'   	  => $funcion,
						 ]);
	}
	public function actionAjaxDetallePedido(Request $request)
	{
		$pedido_id_encriptado 		= 	$request['pedido_id'];
		$pedido_id 					= 	$request['pedido_id'];
		$data_json_detalle 			= 	$request['data_json_detalle'];
		$array_detalle_pedido 		=   json_decode($data_json_detalle);


		$pedido_id 					= 	$this->funciones->desencriptar_id('1CIX-'.$pedido_id,8);

		$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();

		$detalle_pedido 			=   WEBDetallePedido::where('pedido_id','=',$pedido_id)
	    								->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.detallepedidos.estado_id')
										->where('activo','=',1)
										->get();

		$funcion 					= 	$this;			

	    $empresas 					= 	STDEmpresa::where('COD_ESTADO','=','1')->where('IND_SISTEMA','=','1')
	    					 			->pluck('NOM_EMPR','COD_EMPR')
										->toArray();
		$comboempresas 				= 	$empresas;


		return View::make('pedido/ajax/modaldetallepedido',
						 [
							 'pedido_id'   				=> $pedido_id_encriptado,
							 'pedido'   				=> $pedido,
							 'detalle_pedido'   		=> $detalle_pedido,
							 'comboempresas'   			=> $comboempresas,
							 'funcion'   				=> $funcion,
							 'array_detalle_pedido'   	=> $array_detalle_pedido,
						 ]);
	}


	public function actionListarPedido($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $fechainicio  		= 	$this->fin;
	    $fechafin  			= 	$this->fin;

	    $listapedidos		= 		WEBPedido::where('activo','=',1)
	    							->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
	    							->where('usuario_crea','=',Session::get('usuario')->id)
									//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)	    							
				    				->where('fecha_venta','>=', $fechainicio)
				    				->where('fecha_venta','<=', $fechafin)
	    							->orderBy('fecha_venta', 'desc')
	    							->get();

		$funcion 			= 	$this;	

		return View::make('pedido/listapedido',
						 [
						 	'idopcion' 		=> $idopcion,
						 	'listapedidos' 	=> $listapedidos,
						 	'funcion' 		=> $funcion,
						 	'fechainicio' 	=> $fechainicio,
						 	'fechafin' 		=> $fechafin,
						 ]);

	}

	public function actionAjaxListarTomaPedidoVendedor(Request $request)
	{

		$finicio 		=  date_format(date_create($request['finicio']), 'd-m-Y');
		$ffin 			=  date_format(date_create($request['ffin']), 'd-m-Y');


	    $listapedidos	= 	WEBPedido::where('activo','=',1)
							->leftJoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'web.pedidos.estado_id')
							->where('usuario_crea','=',Session::get('usuario')->id)
							//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
							->where('centro_id','=',Session::get('centros')->COD_CENTRO)
				    		->where('fecha_venta','>=', $finicio)
				    		->where('fecha_venta','<=', $ffin)
							->orderBy('fecha_venta', 'desc')
							->get();
		$funcion 		= 	$this;	

		return View::make('pedido/ajax/listatomapedidovendedor',
						 [
						 	'listapedidos' 	=> $listapedidos,
						 	'funcion' 		=> $funcion,
						 	'fechainicio' 	=> $finicio,
						 	'fechafin' 		=> $ffin,
						 	'ajax'   		=> true,
						 ]);

	}


	
	public function actionAgregarOrdenPedido($idopcion ,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		if($_POST)
		{

			try{

				DB::beginTransaction();

				$productos 					= 	$request['productos'];

				$total 						=   $this->funciones->calcular_cabecera_total($productos);
				$codigo 					= 	$this->funciones->generar_codigo('WEB.pedidos',8);
				$idpedido 					= 	$this->funciones->getCreateIdMaestra('WEB.pedidos');
				$cuenta_id 					= 	$this->funciones->desencriptar_id($request['cuenta'],10);
				$cliente_id 				= 	$this->funciones->desencriptar_id($request['cliente'],10);
				$tipocambio 				= 	$this->funciones->tipo_cambio();
				$direcion_entrega_id 		= 	$request['direccion_entrega'];
				$moneda_id 					= 	'MON0000000000001';
				$moneda_nombre 				= 	'SOLES';

				//PEDIDO
				$cabecera            	 	=	new WEBPedido;
				$cabecera->id 	     	 	=  	$idpedido;
				$cabecera->codigo 	    	=  	$codigo;
				$cabecera->igv 	    		=  	$this->funciones->calculo_igv($total);
				$cabecera->subtotal 	    =  	$this->funciones->calculo_subtotal($total);
				$cabecera->total 	    	=  	$total;
				$cabecera->estado 	    	=  	'EM';
				$cabecera->cuenta_id 	    =  	$cuenta_id; 
				$cabecera->cliente_id 	    =  	$cliente_id;

				$cabecera->tipo_cambio 	    =  	$tipocambio->CAN_COMPRA; 
				$cabecera->moneda_id 	    =  	$moneda_id;
				$cabecera->moneda_nombre 	=  	$moneda_nombre; 



				$cabecera->direccion_entrega_id 	    =  	$direcion_entrega_id;
				$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
				$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
				$cabecera->fecha_venta 	 	=   $this->fin;
				$cabecera->fecha_time_venta =   $this->fechaactual;
				$cabecera->fecha_crea 	 	=   $this->fechaactual;
				$cabecera->usuario_crea 	=   Session::get('usuario')->id;

				//adicional

				$cabecera->fecha_despacho   			= 	$request['fecha_entrega'];
				$cabecera->estado_id   					= 	'EPP0000000000002';
				$cabecera->tipopago_id   				= 	$request['condicion_pago'];
				$cabecera->recibo_conformidad  			= 	$request['recibo'];
				$cabecera->glosa  						= 	$request['obs'];
				$cabecera->ind_notificacion 	    	=  	0;
				$cabecera->ind_notificacion_autorizacion 	=   -1;
				$cabecera->ind_notificacion_despacho 	=   -1;
				$np=$cabecera;
				$cabecera->save();

				//DETALLE PEDIDO

				$productos 					= 	json_decode($productos, true);

				foreach($productos as $obj){

					$iddetallepedido 			= 	$this->funciones->getCreateIdMaestra('WEB.detallepedidos');
					$precio_producto 			=  	(float)$obj['precio_producto'];
					$cantidad_producto 			=  	(float)$obj['cantidad_producto'];
					$total_producto 			= 	$precio_producto*$cantidad_producto;
					$producto_id 				= 	$this->funciones->desencriptar_id($obj['prefijo_producto'].'-'.$obj['id_producto'],13);

					$cabecera            	 	=	new WEBDetallePedido;
					$cabecera->id 	     	 	=  	$iddetallepedido;
					$cabecera->precio 	    	=  	$precio_producto;
					$cabecera->cantidad 	    =  	$cantidad_producto;
					$cabecera->igv 	    		=  	$this->funciones->calculo_igv($total_producto);
					$cabecera->subtotal 	    =  	$this->funciones->calculo_subtotal($total_producto);
					$cabecera->total 	    	=  	$total_producto;
					$cabecera->pedido_id 	    =  	$idpedido;
					$cabecera->producto_id 	    =  	$producto_id;
					$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
					$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
					$cabecera->fecha_crea 	 	=   $this->fechaactual;
					$cabecera->usuario_crea 	=   Session::get('usuario')->id;
					$cabecera->save();
				}			

				DB::commit();
				//

 				return Redirect::to('/gestion-de-toma-de-pedido/'.$idopcion)->with('bienhecho', 'Pedido '.$codigo.' registrado con exito');

			}catch(Exception $ex){
				DB::rollback();
				return Redirect::to('/gestion-de-toma-de-pedido/'.$idopcion)->with('errorbd', 'Ocurrio un error inesperado. Porfavor contacte con el administrador del sistema');	
			}

		}else{


			//adicionar clientes
			$id_vendedor_adicionar = '';
			if(Session::get('usuario')->fuerzaventa_id=='JVE0000000000016'){
				$id_vendedor_adicionar = 'adicionar';
			}


		    $listaclientes 		= 	WEBListaCliente::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
									->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									->Adicionarvendedor($id_vendedor_adicionar)
									->orderBy('NOM_EMPR', 'asc')
									->get();
	
		
			$listaproductos 	= 	DB::table('WEB.LISTAPRODUCTOSAVENDER')
									->where('IND_MOVIL','=',1)
		    					 	->orderBy('NOM_PRODUCTO', 'asc')->get();


			return View::make('pedido/ordenpedido',
						[				
						  	'idopcion'  			=> $idopcion,
						  	'listaclientes'  		=> $listaclientes,
						  	'listaproductos'  		=> $listaproductos,
						]);
		}
	}
	public function actionAjaxDireccioncliente(Request $request)
	{

		$data_icl 					=  	$request['data_icl']; //id_cliente
		$data_pcl 					=  	$request['data_pcl']; //prefijo_cliente
		$data_icu 					=  	$request['data_icu']; //id_contrato
		$data_pcu 					=  	$request['data_pcu']; //prefijo_contrato
		$data_ncl 					=  	$request['data_ncl']; //nombre_cliente
		$data_dcl 					=  	$request['data_dcl']; //documento_cliente
		$data_ccl 					=  	$request['data_ccl']; //cuenta_cliente

		$cliente_id 				= 	$this->funciones->desencriptar_id($data_pcl.'-'.$data_icl,10);

	    $direcciones 				= 	DB::table('WEB.LISTADIRECCION')
	    					 			->orderBy('IND_DIRECCION_FISCAL', 'desc')
	    					 			->where('COD_EMPR','=',$cliente_id)
	    					 			->pluck('NOM_DIRECCION','COD_DIRECCION')
										 ->toArray();
										 
		$pagocontado                = DB::table('CMP.CATEGORIA')
										 ->where('COD_CATEGORIA','=','TIP0000000000001')
										 ->pluck('NOM_CATEGORIA','COD_CATEGORIA');
										 
		$tipopago                   =   DB::table('CMP.CATEGORIA as CA')
		                                ->leftJoin('WEB.reglacreditoclientes as RC', 'CA.COD_CATEGORIA', '=', 'RC.condicionpago_id')
										->orderBy('NOM_CATEGORIA', 'desc')
										->where('COD_ESTADO','=',1)
										->where('COD_CATEGORIA','<>','TIP0000000000001')
										->where('RC.cliente_id','=',$cliente_id)
										->pluck('NOM_CATEGORIA','COD_CATEGORIA')
										->union($pagocontado)
										->toArray();

		$reglacredito 		= 	WEBReglaCreditoCliente::where('cliente_id','=',$cliente_id)
										->where('activo',1)
										->get();
			
		$saldocli=DB::select('exec RPS.SALDO_TRAMO_CUENTA ?,?,?,?,?,?,?,?,?,?,?', array('','','','',date("Y-m-d"),$cliente_id,'TCO0000000000068','','','',''));

		$combotipopago              =   array('' => "Seleccione el tipo de pago") + $tipopago;
		$combodirecciones  			= 	array('' => "Seleccione dirección") + $direcciones;



		return View::make('pedido/ajax/direccion',
						 [
							'combodirecciones' 	=> $combodirecciones,
							'combotipopago'     => $combotipopago,
						 	'data_icl' 			=> $data_icl,
						 	'data_pcl' 			=> $data_pcl,
						 	'data_icu' 			=> $data_icu,
						 	'data_pcu' 			=> $data_pcu,
						 	'data_ncl' 			=> $data_ncl,
						 	'data_dcl' 			=> $data_dcl,
							'data_ccl' 		    => $data_ccl,
							'reglacredito'      => $reglacredito,
							'saldocli'          => $saldocli

						 ]);
	}
	public function actionAjaxReglaProducto(Request $request)
	{

		$data_ipr 					=  	$request['data_ipr']; 
		$data_ppr 					=  	$request['data_ppr']; 
		$data_npr 					=  	$request['data_npr']; 
		$data_upr 					=  	$request['data_upr']; 
	
		$cliente_id 					=  	$this->funciones->desencriptar_id($request['cli_id'],10);
		$cuenta_id                  = $this->funciones->desencriptar_id($request['cuenta_id'],10);

		$producto_id 				= 	$this->funciones->desencriptar_id($data_ppr.'-'.$data_ipr,13);
	

		$reglas		= 	WEBReglaProductoCliente::where('producto_id','=',$producto_id)
						->where('cliente_id','=',$cliente_id)
						->where('contrato_id','=',$cuenta_id)
						->where('activo',1)
						->get();

		$precioregular= WEBPrecioProductoContrato::where('producto_id','=',$producto_id)
		->where('contrato_id','=',$cuenta_id)
		->where('activo',1)
		->get();

		$precioestandar=WEBPrecioProducto::where('producto_id',"=",$producto_id)
		->where('activo',1)
		->get();

		return View::make('pedido/ajax/reglaproducto',
						 [
							 'producto_id' 	=> $producto_id,
							 'data_ipr'     => $data_ipr,
							 'data_ppr'     => $data_ppr,
							 'data_npr'     => $data_npr,
							 'data_upr'     => $data_upr,
							 'reglas'       =>$reglas,
							 'precioregular'=>$precioregular,
							 'precioestandar'=>$precioestandar
						 ]);
	}
	public function actionAjaxDeudaSectorizada(Request $request)
	{

		$data_icl					=  	$request['data_icl']; 
		$data_pcl 					=  	$request['data_pcl']; 
		
	

		$cliente_id 				= 	$this->funciones->desencriptar_id($data_pcl.'-'.$data_icl,10);

		// $reglas		= 	WEBReglaProductoCliente::where('producto_id','=',$producto_id)
		// 				->where('activo',1)
		// 				->get();

		

		$saldocuenta=DB::select('exec RPS.SALDO_TRAMO_CUENTA ?,?,?,?,?,?,?,?,?,?,?', array('','','','',date("Y-m-d"),$cliente_id ,'TCO0000000000068','','','',''));

		return [
		
			'saldocuenta' => $saldocuenta
		];
	}

	public function RepSalida()
    {
	   
		if($_POST)
		{

	   $pedido 			= json_decode($request['pedido'], true);
	
       $data = [
          'title' => 'First PDF for Medium',
          'heading' => 'Hello from 99Points.info',
          'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.' ];
        
        $pdf = PDF::loadView('emails/notificacionejecucion', $data);  
		return $pdf->download('medium.pdf');

	   }
    }
}
