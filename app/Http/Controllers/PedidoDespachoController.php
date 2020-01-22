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


	public function actionAjaxModalAgregarOrdenCenPedido(Request $request)
	{

		$data_orden_cen 					= 	$request['data_orden_cen'];
		$grupo 								= 	(int)$request['grupo'];




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
										            "rowspan" 					=> '0',
										        );

				$rowspan 	= 	$rowspan + 1;
				array_push($array_detalle_producto,$array_nuevo_producto);

			}

			// modificar un valor en array
			$array_detalle_producto = $this->funciones->modificarmultidimensionalarray($array_detalle_producto,'rowspan',$rowspan,$orden->NRO_ORDEN_CEN);

		}



		// ordenar el array por grupo
		$array_detalle_producto = $this->funciones->ordermultidimensionalarray($array_detalle_producto,'grupo',false);



		$funcion 	= 	$this;

		return View::make('despacho/ajax/alistapedido',
						 [
						 	'array_detalle_producto' 				=> $array_detalle_producto,
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

			return View::make('despacho/crearordenpedidodespacho',
							 [
							 	'idopcion' 			=> $idopcion,
								'comboclientes' 	=> $comboclientes,						
								'inicio'			=> $this->inicio,
								'hoy'				=> $this->fin,
							 	'grupo' 			=> $grupo,
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
