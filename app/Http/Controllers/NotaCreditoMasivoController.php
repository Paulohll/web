<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\Biblioteca\NotaCredito;
use App\Biblioteca\OsirisMasivo;
use App\WEBDocumentoNotaCredito;

use View;
use Session;
use Hashids;
use Keygen;
use Nexmo;

class NotaCreditoMasivoController extends Controller
{


	public function actionCrearNotaCreditoMasivo($idopcion,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		if($_POST)
		{


			try{

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
			}

		}else{
                                   
			$comboclientes				= 	$this->funciones->combo_clientes_cuenta();

			return View::make('notacreditomasivo/crearnotacreditomasivo',
							 [
							 	'idopcion' 			=> $idopcion,
								'comboclientes' 	=> $comboclientes,						
								'inicio'			=> $this->inicio,
								'hoy'				=> $this->fin,
							 ]);
		}
	}



	public function actionAjaxDetalleProductoBoletaNC(Request $request)
	{


		$data_serie_correlativo 					= 	$request['data_serie_correlativo'];
		$data_documento_id 							= 	$request['data_documento_id'];
		$data_array_productos 						= 	json_decode($request['data_array_productos'], true);
		$funcion  									= 	$this;

		return View::make('notacreditomasivo/modal/ajax/listadetalleproductonc',
						 [
						 	'lista_detalle_producto' 	=> $data_array_productos,
						 	'data_serie_correlativo' 	=> $data_serie_correlativo,
						 	'data_documento_id' 		=> $data_documento_id,
						 	'funcion' 					=> $funcion,
						 	'ajax' 						=> true,
						 ]);

	}




	public function actionAjaxModalGenerarNotaCredito(Request $request)
	{


		$datasproductos 				= 	json_decode($request['datasproductos'], true);

		$cuenta_id 						= 	$request['cuenta_id'];
		$data_cod_orden_venta 			= 	$request['data_cod_orden_venta'];
		$serie 							= 	$request['serie'];
		$motivo_id 						= 	$request['motivo_id'];
		$informacionadicional 			= 	$request['informacionadicional'];
		$idopcion 						= 	$request['idopcion'];


		$tipodocumento 					= 	'TDO0000000000003';
		$producto_id 					=   '';
		$lista_documento_asociados 		= 	$this->funciones->lista_referencia_orden_venta($data_cod_orden_venta);
		$array_documentos_id            = 	$this->funciones->colocar_en_array_id_documentos_asociados($lista_documento_asociados);
		$lista_documento_boletas 		= 	$this->funciones->lista_documentos_contables_array($array_documentos_id,$tipodocumento);
	

		$correlativo 					=	0;
		$array_lista_detalle_producto 	=	array();
		$estado_boleta 					=   'libre';


		// RECORRER TODOS LAS BOLETAS DE LA ORDEN SELECCIONADA
		foreach($lista_documento_boletas as $i => $item){

			$estado_boleta 				=   'libre';
			//ver si la boleta esta libre o aun tiene cantidad
			$nota_credito_asociada 		=  	$this->funciones->boleta_o_factura_asociada_nota_credito($item->COD_DOCUMENTO_CTBLE,'TDO0000000000007');				
			if(count($nota_credito_asociada)>0){
				$estado_boleta 			= 	$this->funciones->ind_faltante_en_boletas_nota_credito($item->COD_DOCUMENTO_CTBLE);
			}

			//tiene nota de credito
			if($estado_boleta =='libre' or $estado_boleta == 'parcialmente'){

				$array_detalle_producto 			=	array();
				$datasproductos_actualizado 		=	array();
				$total 								=	0.0;


				foreach($datasproductos as $key => $obj){

					//en caso aya diferencia de cantidad
					$cantidad_diferencia 			= 	0.0;
					if($estado_boleta =='parcialmente'){
						$cantidad_nc_producto   	=   $this->funciones->data_detalle_producto_sum_cantidad($item->COD_DOCUMENTO_CTBLE,$obj['producto_id']);
						if(count($cantidad_nc_producto)>0){
							$cantidad_diferencia 			= 	$cantidad_nc_producto->CAN_PRODUCTO;
						}
					}

					$producto_id 					= 	$obj['producto_id'];
					$cantidad 						= 	(float)$obj['cantidad'];
					$precio 						= 	(float)$obj['precio'];

					$cantidad_m 					= 	$cantidad;
					// Detalle del documento
					$lista_detalle_producto  		= 	$this->funciones->lista_detalle_producto_orden_venta($item->COD_DOCUMENTO_CTBLE,$producto_id);
					$array_nuevo_producto 			=	array();


					// Productos seleccionados esta dentro del Detalle del documento 
					while($row = $lista_detalle_producto->fetch())
					{

						//que el producto este dentro la lista
						if($producto_id == $row['COD_PRODUCTO']){
							// que tenga cantidad por lo menos
							if($cantidad>0){


								$can_producto = (float)$row['CAN_PRODUCTO'] - $cantidad_diferencia;
								//cantidad mayor al detalle
								if($cantidad >= $can_producto){

									$total 						= 	$total + $can_producto*$precio;
									$array_nuevo_producto 		=	array(
															            "producto_id" 		=> $row['COD_PRODUCTO'],
															            "cantidad" 			=> $can_producto,
															            "precio" 			=> $precio,
															        );
									$cantidad_m = $cantidad - $can_producto;

								}else{

									$total 						= 	$total + $cantidad*$precio;
									$array_nuevo_producto 		=	array(
															            "producto_id" 		=> $row['COD_PRODUCTO'],
															            "cantidad" 			=> $cantidad,
															            "precio" 			=> $precio,
															        );
									$cantidad_m = 0;

								}
							}
						}

					}

					/************************************ Array sobrante *******************************/
					$array_sobrante		= 		array(
														"producto_id" 		=> $producto_id,
														"cantidad" 			=> $cantidad_m,
														"precio" 			=> $precio,
													 );
					array_push($datasproductos_actualizado,$array_sobrante);		

					if(count($array_nuevo_producto)>0){
						array_push($array_detalle_producto,$array_nuevo_producto);
					}


				}

				$datasproductos 	=  $datasproductos_actualizado;

				if(count($array_detalle_producto)>0){



					$correlativo 				= 	$correlativo + 1;
					$array_nota_credito 		= 	array(
														"nota_credito" 			=> $correlativo,
											            "documento_id" 			=> $item->COD_DOCUMENTO_CTBLE,
											            "serie_correlativo" 	=> $item->NRO_SERIE.'-'.$item->NRO_DOC,
											            "total" 				=> $total,
											            "detalle_productos" 	=> $array_detalle_producto,
											        );

					array_push($array_lista_detalle_producto,$array_nota_credito);

				}



			}
		}




		$validacion_cantidad_productos = 0;
		$mensaje_validacion = 'La generación de notas de creditos fue exitosa';
		//Productos que aun faltan cantidad 
		foreach($datasproductos as $key => $obj){

			$cantidad 		= 	(float)$obj['cantidad'];
			if($cantidad>0){
				$validacion_cantidad_productos = 1;
				$mensaje_validacion = 'La generación de notas de creditos no se completo (sobrante de cantidades)';
			}

		}
		$funcion  						= $this;
		$notacredito                    = new NotaCredito();

		//dd($array_lista_detalle_producto);

		return View::make('notacreditomasivo/modal/ajax/generacionnotacredito',
						 [
						 	'lista_detalle_producto' 			=> $array_lista_detalle_producto,
						 	'cuenta_id' 						=> $cuenta_id,
						 	'funcion' 							=> $funcion,
						 	'notacredito' 						=> $notacredito,
						 	'validacion_cantidad_productos' 	=> $validacion_cantidad_productos,
						 	'mensaje_validacion' 				=> $mensaje_validacion,
						 	'data_cod_orden_venta' 				=> $data_cod_orden_venta,
						 	'ajax' 								=> true,
						 	'serie' 							=> $serie,
						 	'motivo_id' 						=> $motivo_id,
						 	'informacionadicional' 				=> $informacionadicional,
						 	'idopcion' 							=> $idopcion,
						 ]);




	}




	public function actionAjaxModalDetalleProducto(Request $request)
	{

		$producto_id 					=   '';
		$documento_id 					= $request['documento_id'];
		$cuenta_id 						= $request['cuenta_id'];
		$lista_detalle_producto 		= $this->funciones->lista_detalle_producto_orden_venta($documento_id,$producto_id);
		$documento     					= $this->funciones->data_documento($documento_id);
		$funcion 						= $this;


		return View::make('notacreditomasivo/modal/ajax/listadetalleproducto',
						 [
						 	'lista_detalle_producto' 	=> $lista_detalle_producto,
						 	'cuenta_id' 				=> $cuenta_id,
						 	'funcion' 					=> $funcion,
						 	'ajax' 						=> true,
						 	'documento' 				=> $documento,
						 ]);

	}



	public function actionAjaxOrdenVentaBoletas(Request $request)
	{

		$notacredito                    = new NotaCredito();
		$cuenta_id 						= $request['cuenta_id'];
		$data_cod_orden_venta 			= $request['data_cod_orden_venta'];
		$producto_id 					=   '';
		$lista_detalle_producto 		= $this->funciones->lista_detalle_producto_orden_venta($data_cod_orden_venta,$producto_id);

		//lista de boletas asociadas a una orden de venta (solo clientes otros)
		$lista_documento_asociados 		= $this->funciones->lista_referencia_orden_venta($data_cod_orden_venta);
		$tipodocumento 					= 'TDO0000000000003';
		$array_documentos_id            = $this->funciones->colocar_en_array_id_documentos_asociados($lista_documento_asociados);
		$lista_documento_boletas 		= $this->funciones->lista_documentos_contables_array($array_documentos_id,$tipodocumento);


		$funcion 						= $this;
		$cod_tipo_documento 			= 'TDO0000000000003';

		$combo_series 					= $notacredito->combo_series();

		$motivos_array 					= ['MEM0000000000004','MEM0000000000016','MEM0000000000007'];
		//$motivos_array 					= ['MEM0000000000004','MEM0000000000016'];
		$combo_motivos 					= $notacredito->combo_motivos_documento('TDO0000000000007',$motivos_array);


		return View::make('notacreditomasivo/ajax/ordenventaboleta',
						 [
						 	'lista_detalle_producto' 	=> $lista_detalle_producto,
						 	'lista_documento_asociados' => $lista_documento_asociados,
						 	'lista_documento_boletas'	=> $lista_documento_boletas,
						 	'cuenta_id' 				=> $cuenta_id,
						 	'data_cod_orden_venta' 		=> $data_cod_orden_venta,
						 	'funcion' 					=> $funcion,
						 	'cod_tipo_documento' 		=> $cod_tipo_documento,
						 	'combo_series' 				=> $combo_series,
						 	'combo_motivos' 			=> $combo_motivos,
						 	'ajax' 						=> true,
						 ]);

	}



	public function actionAjaxModalListaOrdenVentaCuenta(Request $request)
	{

		$cuenta_id 			= $request['cuenta_id'];
	    $tipo_documento_id 	= 'TDO0000000000003'; //boletas
	    $fecha_inicio 		= date_format(date_create($this->inicio), 'd-m-Y');
	    $fecha_fin 			= date_format(date_create($this->fin), 'd-m-Y');

		$array_orden 		= $this->funciones->array_orden_venta_documento_fechas_cuenta($tipo_documento_id,$fecha_inicio,$fecha_fin,$cuenta_id);
		$lista_orden 		= $this->funciones->lista_orden_venta_array_orden($array_orden);
		$funcion 			= $this;

		return View::make('notacreditomasivo/modal/ajax/ordenventa',
						 [
						 	'lista_orden' 	=> $lista_orden,
						 	'fecha_inicio' 	=> $fecha_inicio,
						 	'fecha_fin' 	=> $fecha_fin,
						 	'cuenta_id' 	=> $cuenta_id,
						 	'funcion' 		=> $funcion,
						 ]);

	}


	public function actionAjaxModalListaOrdenVentaFechas(Request $request)
	{

		$cuenta_id 			= $request['cuenta_id'];

	    $tipo_documento_id 	= 'TDO0000000000003'; //boletas
	    $fecha_inicio 		= date_format(date_create($request['fechainicio']), 'd-m-Y');
	    $fecha_fin 			= date_format(date_create($request['fechafin']), 'd-m-Y');

		$array_orden 		= $this->funciones->array_orden_venta_documento_fechas_cuenta($tipo_documento_id,$fecha_inicio,$fecha_fin,$cuenta_id);
		$lista_orden 		= $this->funciones->lista_orden_venta_array_orden($array_orden);
		$funcion 			= $this;

		return View::make('notacreditomasivo/modal/ajax/listaordenventa',
						 [
						 	'lista_orden' 	=> $lista_orden,
						 	'fecha_inicio' 	=> $fecha_inicio,
						 	'fecha_fin' 	=> $fecha_fin,
						 	'cuenta_id' 	=> $cuenta_id,
						 	'funcion' 		=> $funcion,
						 ]);

	}






	public function actionListarNotaCreditoMasivo($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listadocumentonotacredito 		= 	WEBDocumentoNotaCredito::join('WEB.documento_asociados', 
	    									'WEB.documento_asociados.documento_nota_credito_id', '=', 'WEB.documento_nota_credito.id')
	    									->where('WEB.documento_nota_credito.empresa_id','=',Session::get('empresas')->COD_EMPR)
	    									->where('WEB.documento_nota_credito.txt_modulo','=','BOLETAS_MASIVAS')
	    									->orderBy('lote', 'asc')
	    									->select('lote','nota_credito_id','codigo','contrato_id','orden_id','total_notacredito','documento_id',
	    									'WEB.documento_nota_credito.fecha_crea',
	    									'WEB.documento_nota_credito.id',
	    									'WEB.documento_nota_credito.centro_id')
	    									->get();
	    									


		$funcion 						= 	$this;
		$notacredito                    =   new NotaCredito();

		return View::make('notacreditomasivo/notacreditoordenventa',
						 [
						 	'idopcion' 								=> $idopcion,
						 	'listadocumentonotacredito' 			=> $listadocumentonotacredito,
						 	'funcion' 								=> $funcion,
						 	'notacredito' 							=> $notacredito,
						 ]);

	}










}

/*
Nexmo::message()->send([
    'to'   => '51973835599',
    'from' => 'frank',
    'text' => 'hola perro neil'
]);
*/