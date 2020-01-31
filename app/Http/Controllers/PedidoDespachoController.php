<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;

use View;
use Session;
use App\Biblioteca\Osiris;
use App\Biblioteca\Funcion;
use PDO;
use Mail;
use PDF;
use App\WEBOrdenDespacho,App\CMPOrden,App\WEBListaCliente;
  
class PedidoDespachoController extends Controller
{


	public function actionAjaxPedidoEliminarFila(Request $request)
	{

		$array_detalle_producto_request 	= 	json_decode($request['array_detalle_producto'],true);
		$array_detalle_producto 			=	array();
		$grupo 								= 	(int)$request['grupo'];
		$correlativo 						= 	(int)$request['correlativo'];
		$fila 								= 	$request['fila'];


		//eliminar la fila del array
		foreach ($array_detalle_producto_request as $key => $item) {
            if((int)$item['correlativo'] == $fila) {
                unset($array_detalle_producto_request[$key]);
            }
		}


	    //agregar a un array nuevo para listar en la vista
		foreach ($array_detalle_producto_request as $key => $item) {
			array_push($array_detalle_producto,$item);
		}

		// ordenar el array por grupo
		$array_detalle_producto = 	$this->funciones->ordermultidimensionalarray($array_detalle_producto,'correlativo',false);		
		$array_detalle_producto = 	$this->funciones->ordermultidimensionalarray($array_detalle_producto,'grupo',false);
		$funcion 				= 	$this;

		return View::make('despacho/ajax/alistapedido',
						 [
						 	'array_detalle_producto' 				=> $array_detalle_producto,
						 	'grupo' 								=> $grupo,
						 	'correlativo' 							=> $correlativo,
						 	'funcion' 								=> $funcion,
						 	'ajax'   		  						=> true,
						 ]);



	}





	public function actionAjaxPedidoCrearMovil(Request $request)
	{

		$array_detalle_producto_request 	= 	json_decode($request['array_detalle_producto'],true);
		$data_producto_pedido 				= 	$request['data_producto_pedido'];
		$array_detalle_producto 			=	array();
		$grupo 								= 	(int)$request['grupo'];
		$correlativo 						= 	(int)$request['correlativo'];


		//el mayor valor numero de movil
		$grupo_mobil_mayor 					=	0;
		foreach ($array_detalle_producto_request as $key => $item) {
            if((int)$item['grupo_movil'] > $grupo_mobil_mayor) {
                $grupo_mobil_mayor = (int)$item['grupo_movil'];
            }
		}


		//agregar el numero de movil y agrupar 	 
		foreach($array_detalle_producto_request as $key => $row) {
			$encontro = array_search($row['correlativo'], array_column($data_producto_pedido, 'correlativo'));
		    if (!is_bool($encontro)){
		    	$array_detalle_producto_request[$key]['grupo_movil'] = $grupo_mobil_mayor + 1;
		    }
	    } 


	    //agregar a un array nuevo para listar en la vista
		foreach ($array_detalle_producto_request as $key => $item) {
			array_push($array_detalle_producto,$item);
		}


		// ordenar el array por grupo movil
		$array_detalle_producto = 	$this->funciones->ordermultidimensionalarray($array_detalle_producto,'grupo_movil',false);
		$nuevo_grupo 		= 	0;
		$i 			 		=  	0;
		$sw 				= 	0;
		$grupo_diferente 	= 	0;
		//inicializar los grupos moviles 
		foreach($array_detalle_producto as $key => $row) {

            if((int)$row['grupo_movil'] > 0) {

            	$grupo_movil_nro    	= 	(int)$row['grupo_movil'];
		    	if($sw == 0){
		    		$grupo_diferente 	= 	$grupo_movil_nro;
		    		$sw 				= 	1;
		    	}

		    	if($grupo_movil_nro <> $grupo_diferente){
		    		$grupo_diferente 	=	$grupo_movil_nro;
		    		$i 					= 	$i + 1;
		    	}
             	$nuevo_grupo 			= 	$grupo_movil_nro - ($grupo_movil_nro-1) + $i;
		    	$array_detalle_producto[$key]['grupo_movil'] = $nuevo_grupo;
            }
	    }


	    //agregar la cantidad de grupo movil correcto
	    $count_grupo = 0;
		foreach($array_detalle_producto as $key => $row) {
			$grupo_movil_nro    	= 	(int)$row['grupo_movil'];
            if($grupo_movil_nro > 0) {
				$count_grupo 										= 	$this->funciones->countgrupomovil($array_detalle_producto,'grupo_movil',$grupo_movil_nro);
				$array_detalle_producto[$key]['grupo_orden_movil'] 	= 	$count_grupo;
            }
	    }



		// ordenar el array por grupo
		$array_detalle_producto = 	$this->funciones->ordermultidimensionalarray($array_detalle_producto,'correlativo',false);		
		$array_detalle_producto = 	$this->funciones->ordermultidimensionalarray($array_detalle_producto,'grupo',false);
		$funcion 				= 	$this;

		return View::make('despacho/ajax/alistapedido',
						 [
						 	'array_detalle_producto' 				=> $array_detalle_producto,
						 	'grupo' 								=> $grupo,
						 	'correlativo' 							=> $correlativo,
						 	'funcion' 								=> $funcion,
						 	'ajax'   		  						=> true,
						 ]);



	}





	public function actionAjaxModalAgregarOrdenCenPedido(Request $request)
	{

		$data_orden_cen 					= 	$request['data_orden_cen'];
		$grupo 								= 	(int)$request['grupo'];
		$correlativo 						= 	(int)$request['correlativo'];

		$array_detalle_producto_request 	= 	json_decode($request['array_detalle_producto'],true);


		$array_detalle_producto 			=	array();

		foreach($data_orden_cen as $obj){

		    $ordencen_id 					= 	$obj['ordencen_id'];
		    $orden 							= 	CMPOrden::where('COD_ORDEN','=',$ordencen_id)->first();
			$lista_detalle_ordencen			= 	$this->funciones->lista_orden_cen_detalle($ordencen_id);
			$array_nuevo_producto 			=	array();
			$grupo 							= 	$grupo + 1;
			$rowspan 						= 	0;

			while($row = $lista_detalle_ordencen->fetch())
			{

				$correlativo 				= 	$correlativo + 1;
				$array_nuevo_producto 		=	array(
													"empresa_cliente_id" 		=> $orden->COD_EMPR_CLIENTE,
													"empresa_cliente_nombre" 	=> $orden->TXT_EMPR_CLIENTE,
													"orden_id" 					=> $row['COD_TABLA'],
													"orden_cen" 				=> $orden->NRO_ORDEN_CEN,
													"fecha_pedido" 				=> $this->fin,
													"fecha_entrega" 			=> $this->fin,
										            "producto_id" 				=> $row['COD_PRODUCTO'],
										            "nombre_producto" 			=> $row['TXT_NOMBRE_PRODUCTO'],
										            "cantidad" 					=> $row['CAN_PRODUCTO'],
										            "grupo" 					=> $grupo,
										            "grupo_orden" 				=> '0',
										            "grupo_movil" 				=> '0',
										            "grupo_orden_movil" 		=> '0',
										            "correlativo" 				=> $correlativo
										        );

				$rowspan 	= 	$rowspan + 1;
				array_push($array_detalle_producto,$array_nuevo_producto);

			}

			// modificar un valor en array
			$array_detalle_producto = $this->funciones->modificarmultidimensionalarray($array_detalle_producto,'grupo_orden',$rowspan,$orden->NRO_ORDEN_CEN);

		}

		if(count($array_detalle_producto_request)>0){
			foreach ($array_detalle_producto_request as $key => $item) {
				array_push($array_detalle_producto,$item);
			}
		}

		// ordenar el array por grupo
		$array_detalle_producto = $this->funciones->ordermultidimensionalarray($array_detalle_producto,'grupo',false);
		$funcion 	= 	$this;

		return View::make('despacho/ajax/alistapedido',
						 [
						 	'array_detalle_producto' 				=> $array_detalle_producto,
						 	'grupo' 								=> $grupo,
						 	'correlativo' 							=> $correlativo,
						 	'funcion' 								=> $funcion,
						 	'ajax'   		  						=> true,
						 ]);

	}

	public function actionListarGeneracionPedido($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $listaordendespacho 			= 	WEBOrdenDespacho::get();					
		$funcion 						= 	$this;


		return View::make('despacho/listaordendespacho',
						 [
						 	'idopcion' 								=> $idopcion,
						 	'listaordendespacho' 					=> $listaordendespacho,
						 	'funcion' 								=> $funcion,
						 ]);

	}



	public function actionCrearPedidoDepacho($idopcion,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		if($_POST)
		{


			/*try{

				DB::beginTransaction();

				$osirismasivo 							= 	new OsirisMasivo();
				$notacredito                    		=   new NotaCredito();

				$array_lista_detalle_producto 			= 	json_decode($request['array_lista_detalle_producto']);
				$contrato_id 							= 	$request['cuenta_id'];
				$motivo_id 								= 	$request['motivo_id'];
				$glosa 									= 	$request['glosa'];
				$informacionadicional 					= 	$request['informacionadicional'];
				$serie 									= 	$request['serie'];
				$direccion_id 							= 	$notacredito->direccion_cuenta_boleta($contrato_id);
				$funcion  								= 	$this;
				$data_cod_orden_venta  					= 	$request['data_cod_orden_venta'];
	            $lote                         			=   $this->funciones->generar_lote('WEB.documento_nota_credito',6);


				foreach($array_lista_detalle_producto as $key => $obj){

					$numero_documento					= 	$notacredito->numero_documento($serie,'TDO0000000000007');
					$totalnotacredito					= 	(float)$obj->total;
					$documento_relacionado_id			= 	$obj->documento_id;
					$array_productos					= 	$obj->detalle_productos;

					$respuesta 							=  	$osirismasivo->guardar_nota_credito($contrato_id,$direccion_id,$serie,$motivo_id,$glosa,$informacionadicional,$numero_documento,$funcion,$totalnotacredito,$documento_relacionado_id,$array_productos,$data_cod_orden_venta,$lote);


				}	

				$nota_credito 							=	WEBDocumentoNotaCredito::where('lote','=',$lote)->get();
				DB::commit();
	 			return Redirect::to('/gestion-de-generacion-nota-credito-masivo/'.$idopcion)->with('bienhecho', ' '.count($nota_credito).' notas de creditos creadas - lote('.$lote.')');

			}catch(Exception $ex){
				DB::rollback();
				return Redirect::to('/gestion-de-orden-compra-servicios/'.$idopcion)->with('errorbd', 'Ocurrio un error inesperado. Porfavor contacte con el administrador del sistema : '.$ex);	
			}*/

		}else{
                                   
			$comboclientes				= 	$this->funciones->combo_clientes_cuenta();
			$grupo						= 	0;
			$correlativo				= 	0;


			return View::make('despacho/crearordenpedidodespacho',
							 [
							 	'idopcion' 			=> $idopcion,
								'comboclientes' 	=> $comboclientes,						
								'inicio'			=> $this->inicio,
								'hoy'				=> $this->fin,
							 	'grupo' 			=> $grupo,
							 	'correlativo' 		=> $correlativo,
							 ]);
		}
	}



	public function actionAjaxModalListaOrdenCenProducto(Request $request)
	{


		$cuenta_id 						= 	$request['cuenta_id'];
		$funcion 						= 	$this;

	    $listaproductos 				= 	DB::table('WEB.LISTAPRODUCTOSAVENDER')
				    					 	->orderBy('NOM_PRODUCTO', 'asc')
				    					 	->get();


		/******* LISTA ORDEN CEN  **********/
		$empresa_id 					= 	Session::get('empresas')->COD_EMPR;
		$centro_id 						= 	Session::get('centros')->COD_CENTRO;
		$cliente 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();
		$fecha_inicio 					= 	$this->fecha_menos_treinta_dias;
		$fecha_fin 						= 	$this->inicio;
		$listaordencen					= 	$this->funciones->lista_orden_cen($empresa_id,$cliente->id,$centro_id,$fecha_inicio,$fecha_fin);
	 	



		return View::make('despacho/modal/ajax/ordencenproducto',
						 [
						 	'cuenta_id' 				=> $cuenta_id,
						 	'listaproductos' 			=> $listaproductos,
						 	'listaordencen' 			=> $listaordencen,
						 	'funcion' 					=> $funcion,
						 	'ajax' 						=> true,
						 ]);


	}




//actionAjaxModalAgregarProductosOrdenCen




}
