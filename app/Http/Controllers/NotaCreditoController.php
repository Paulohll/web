<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\Biblioteca\NotaCredito;
use App\Biblioteca\Osiris;
use App\WEBRegla,App\CMPDocumentoCtble,App\CMPDetalleProducto,App\WEBOrdenDetalleRegla;
use App\WEBListaCliente,App\CMPOrden;
use App\WEBDocumentoNotaCredito,App\WEBDocumentoAsociados,App\WEBDetalleDocumentoAsociados;
use View;
use Session;
use Hashids;
use Keygen;

class NotaCreditoController extends Controller
{


	public function actionAjaxAgregarReglaOrdenCen(Request $request)
	{


	    $cuenta_id 							= 	$request['cuenta_id'];
	    $fechainicio 						= 	$request['fechainicio'];
	    $fechafin 							= 	$request['fechafin'];
	    $ordencen_id 						= 	$request['ordencen_id'];
	    $producto_id 						= 	$request['producto_id'];
	    $regla_id 							= 	$request['array_reglas'];
		$array_reglas_id 					= 	explode(',', $regla_id);
		$notacredito                		=   new NotaCredito();


		$mensaje 							=  	'Se agrego la regla exitosamente';
		$response 							= 	$notacredito->agregar_reglas_orden_cen($mensaje,'OC',$ordencen_id,$producto_id,$array_reglas_id,'OV');
		if($response[0]['error']){echo json_encode($response); exit();}

		echo json_encode($response);

	}





	public function actionAjaxListaAgregarOrdenCenNotaCredito(Request $request)
	{

		set_time_limit(0);

		$notacredito                    =   new NotaCredito();
		$cuenta_id 						=  	$request['cuenta_id'];
		$fechainicio 					=  	date_format(date_create($request['fechainicio']), 'Y-m-d');		
		$fechafin 						=  	date_format(date_create($request['fechafin']), 'Y-m-d');

		if(is_array($request['regla_id'])){
			$regla_id 						=  	$request['regla_id'];
		}else{

			$array_reglas_id 				= 	explode(',', $request['regla_id']);
			$regla_id 						=  	$array_reglas_id;
		}





		$idopcion 						=  	$request['opcion'];
		$iddocumentonotacredito 		=  	$request['iddocumentonotacredito'];

		$documentonotacredito 			=  	WEBDocumentoNotaCredito::where('id','=',$iddocumentonotacredito)->first();

        $array_orden_cen           		=   WEBOrdenDetalleRegla::join('CMP.ORDEN', 'CMP.ORDEN.COD_ORDEN', '=', 'WEB.ordendetallereglas.orden_id')
        									->where('WEB.ordendetallereglas.estado','=','OC')
	                                        ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
	                                        ->where('WEB.ordendetallereglas.activo','=',1)
	                                        ->whereRaw('Convert(varchar(10), WEB.ordendetallereglas.fecha_crea, 120) >= ?', [$fechainicio])
	                                        ->whereRaw('Convert(varchar(10), WEB.ordendetallereglas.fecha_crea, 120) <= ?', [$fechafin])
	                                        ->where('WEB.ordendetallereglas.activo','=',1)
	                                        ->whereIn('regla_id',$regla_id)
	                                        ->whereIn('proceso_id',['OV','OC'])
	                                        ->groupBy('orden_id')
	                                        ->pluck('orden_id')
	                                        ->toArray();


        $lista_ordenes           		=   CMPOrden::where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
		                                    //->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									        ->where(function ($query){
							                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
							                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
											})
		                                    ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                                    ->where('CMP.ORDEN.FEC_ORDEN','>=', $fechainicio)
		                                    ->where('CMP.ORDEN.FEC_ORDEN','<=', $fechafin)
		                                    ->where('CMP.ORDEN.IND_MATERIAL_SERVICIO','=','M')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_MODULO','=','MSI0000000000010')
		                                    ->whereIn('CMP.ORDEN.COD_ORDEN',$array_orden_cen)
		                                    ->get();


		$funcion 						= 	$this; 
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();

		return View::make('notacredito/ajax/agregaroredencen',
						 [
							'lista_ordenes'   		=> $lista_ordenes,
						 	'funcion' 				=> $funcion,
						 	'contrato' 				=> $contrato,
						 	'notacredito' 			=> $notacredito,
						 	'regla_id' 				=> $regla_id,
						 	'fechainicio' 			=> $fechainicio,
						 	'fechafin' 				=> $fechafin,
						 	'idopcion' 				=> $idopcion,
						 	'documentonotacredito'  => $documentonotacredito,
						 ]);

	}







	public function actionAgregarOrdenCen($idopcion,$iddocumentonotacredito,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		if($_POST)
		{


			set_time_limit(0);
			$contrato_id 				=  	$request['contrato_id'];
			$facturasnotacredito 		=  	json_decode($request['facturasnotacredito'], true);
			$reglas_id 					= 	explode(',', $request['reglas']);
			$notacredito                =   new NotaCredito();


			/****************** REGISTRO DE CABECERA *********************/
			$idcabecera 				= 	$iddocumentonotacredito;
			$documentonotacredito 		=  	WEBDocumentoNotaCredito::where('id','=',$iddocumentonotacredito)->first();

			$response 						= 	$notacredito->nota_credito_cerrada($iddocumentonotacredito);
				if($response){return Redirect::back()->withInput()->with('errorbd', 'Nota de credito ('.$documentonotacredito->codigo.') se encuentra en estado cerrado');}



			/****************** REGISTRO DE DETALLE FACTURAS *********************/


				$totalfactura 					=   0.0000;
				$totalreglas 					=   0.0000;


				foreach($facturasnotacredito as $obj){

					$documento_id 								=	$obj['documento_id'];
					$orden_id 									=	$obj['orden_id'];

					$iddetalle 									= 	$this->funciones->getCreateIdMaestra('WEB.documento_asociados');
	            	$documento 	    							=   CMPDocumentoCtble::where('COD_DOCUMENTO_CTBLE','=',$documento_id)->first();

	            	$totalfactura 								=	$totalfactura + $documento->CAN_TOTAL;
	            	$totalreglas 								=	$totalreglas + $notacredito->monto_descuento_nota_credito_factura($documento_id,'',$reglas_id,$orden_id);

					$detalle            	 					=	new WEBDocumentoAsociados;
					$detalle->id 	    						=  	$iddetalle;
					$detalle->total_factura 					=  	$documento->CAN_TOTAL;
					$detalle->total_reglas 						=  	$notacredito->monto_descuento_nota_credito_factura($documento_id,'',$reglas_id,$orden_id);
					$detalle->documento_nota_credito_id 		=  	$idcabecera;

					$detalle->orden_id 							=  	$orden_id;
					$detalle->documento_id 						=  	$documento_id;
					$detalle->fecha_crea 	    				=  	$this->fechaactual;
					$detalle->usuario_crea 						=  	Session::get('usuario')->id;
					$detalle->empresa_id 						=   Session::get('empresas')->COD_EMPR;
					$detalle->centro_id 						=   Session::get('centros')->COD_CENTRO;
					$detalle->save();


					/****************** REGISTRO DE DETALLE REGLAS *********************/

			        $lista_productos    	=   CMPDetalleProducto::where('CMP.DETALLE_PRODUCTO.COD_ESTADO','=',1)
			                                    ->where('CMP.DETALLE_PRODUCTO.COD_TABLA','=',$documento_id)->get();

					foreach ($reglas_id as &$regla_id) {

			            foreach($lista_productos as $index => $item){
			    

			            	$iddetaller 								= 	$this->funciones->getCreateIdMaestra('WEB.detalle_documento_asociados');
			            	$total_regla          						=   $notacredito->monto_descuento_nc_documentocontable_producto_individual($documento_id,'',$item->COD_PRODUCTO,$orden_id,$regla_id);

			            	$ordendetallereglas 						= 	WEBOrdenDetalleRegla::where('estado','=','OC')
			            													->where('orden_id','=',$orden_id)
			            													->where('producto_id','=',$item->COD_PRODUCTO)
			            													->where('activo','=',1)
			            													->where('regla_id','=',$regla_id)
			            													->first();

			            	if($total_regla>0){

								$detaller            	 					=	new WEBDetalleDocumentoAsociados;
								$detaller->id 	    						=  	$iddetaller;
								$detaller->total_producto 					=  	$item->CAN_VALOR_VTA;
								$detaller->total_reglas 					=  	$total_regla;
								$detaller->fecha_crea 	    				=  	$this->fechaactual;
								$detaller->usuario_crea 					=  	Session::get('usuario')->id;
								$detaller->documento_asociados_id 			=  	$iddetalle;
								$detaller->documento_id 					=  	$documento_id;
								$detaller->producto_id 						=  	$item->COD_PRODUCTO;
								$detaller->regla_id 						=  	$regla_id;
								$detaller->ordendetallereglas_id 			=  	$ordendetallereglas->id;
								$detaller->cantidad                   		=   $item->CAN_PRODUCTO;
                        		$detaller->precio                     		=   $item->CAN_PRECIO_UNIT;
								$detaller->empresa_id 						=   Session::get('empresas')->COD_EMPR;
								$detaller->centro_id 						=   Session::get('centros')->COD_CENTRO;
								$detaller->save();

								$ordendetallereglas->proceso_id 			=  	'NC';
								$ordendetallereglas->save();



			            	}
			            }

					}

				}



			$sumastotales 			= 		WEBDocumentoAsociados::where('documento_nota_credito_id','=',$iddocumentonotacredito)
											->select(DB::raw('sum(total_factura) as totalf,sum(total_reglas) as totalr'))
											->where('activo','=',1)
											->first();

			$cabeceram            	 		 =	WEBDocumentoNotaCredito::find($idcabecera);
			$cabeceram->total_factura 	     =  $sumastotales->totalf;
			$cabeceram->total_reglas 	 	 =  $sumastotales->totalr;			
			$cabeceram->save();


 			return Redirect::to('/gestion-de-nota-credito-autoservicios/'.$idopcion)->with('bienhecho', 'Reglas asociadas lote('.$documentonotacredito->codigo.') modificada con exito');


		}else{
 

			$notacredito                    =   new NotaCredito();
			$iddocumentonotacredito 		= 	$this->funciones->decodificarmaestra($iddocumentonotacredito);
			$documentonotacredito 			=  	WEBDocumentoNotaCredito::where('id','=',$iddocumentonotacredito)->first();

			$response 						= 	$notacredito->nota_credito_cerrada($iddocumentonotacredito);
			if($response){return Redirect::back()->withInput()->with('errorbd', 'Nota de credito ('.$documentonotacredito->codigo.') se encuentra en estado cerrado');}



			$comboreglas					= 	$notacredito->combo_reglas_nc_seleccionadas($iddocumentonotacredito);
			$idselectreglas					= 	$notacredito->id_reglas_nc_seleccionadas($iddocumentonotacredito);
			$comboclientes					= 	$this->funciones->combo_clientes_cuenta_seleccionada($documentonotacredito->contrato_id);


			return View::make('notacredito/agregarordencen',
							 [
							 	'idopcion' 			=> $idopcion,
								'comboclientes' 	=> $comboclientes,
								'idselectreglas' 	=> $idselectreglas,
								'comboreglas' 		=> $comboreglas,
								'documentonotacredito' 		=> $documentonotacredito,
								'iddocumentonotacredito' 		=> $iddocumentonotacredito,
								'inicio'			=> $this->inicio,
								'hoy'				=> $this->fin,
							 ]);
		}
	}





	public function actionEliminarOrdenCen($idopcion,$iddocumentonotacredito)
	{


		$notacredito                    =   new NotaCredito();

		$iddocumentonotacredito 		= 	$this->funciones->decodificarmaestra($iddocumentonotacredito);
		$documentonotacredito 			=  	WEBDocumentoNotaCredito::where('id','=',$iddocumentonotacredito)->first();

		$response 						= 	$notacredito->nota_credito_cerrada($iddocumentonotacredito);
			if($response){return Redirect::back()->withInput()->with('errorbd', 'Nota de credito ('.$documentonotacredito->codigo.') se encuentra en estado cerrado');}



		$documentoasociados				=  	WEBDocumentoAsociados::where('documento_nota_credito_id','=',$iddocumentonotacredito)
											->where('activo','=',1)
											->pluck('orden_id')
											->toArray();



		$regla_id						=  	WEBDetalleDocumentoAsociados::join('WEB.documento_asociados', 'WEB.documento_asociados.id', '=', 'WEB.detalle_documento_asociados.documento_asociados_id')
											->where('WEB.documento_asociados.documento_nota_credito_id','=',$iddocumentonotacredito)
	                                        ->where('WEB.detalle_documento_asociados.activo','=',1)
	                                        ->where('WEB.documento_asociados.activo','=',1)
											->pluck('regla_id')
											->toArray();


		$cuenta_id 						=  	$documentonotacredito->contrato_id;

        $lista_ordenes           		=   CMPOrden::where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
		                                    //->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									        ->where(function ($query){
							                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
							                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
											})
		                                    ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                                    ->where('CMP.ORDEN.IND_MATERIAL_SERVICIO','=','M')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_MODULO','=','MSI0000000000010')
		                                    ->whereIn('CMP.ORDEN.COD_ORDEN',$documentoasociados)->get();

		$direccion 						= 	$notacredito->direccion_cuenta($cuenta_id);
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();

		$funcion 						= 	$this;



		return View::make('notacredito/eliminarordencen',
						 [
							'lista_ordenes'   		=> $lista_ordenes,
						 	'funcion' 				=> $funcion,
						 	'notacredito' 			=> $notacredito,
							'regla_id' 				=> $regla_id,
						 	'direccion'	 			=> $direccion,
						 	'contrato'	 			=> $contrato,
						 	'documentonotacredito'  => $documentonotacredito,
							'iddocumentonotacredito' => $iddocumentonotacredito,
						 	'idopcion'  			=> $idopcion,

						 ]);



	}


	public function actionAjaxEliminarOrdenCen(Request $request)
	{


	    $contrato_id 						= 	$request['contrato_id'];
	    $documento_id 						= 	$request['documento_id'];
	    $referencia_id 						= 	$request['referencia_id'];
	    $ordencen_id 						= 	$request['ordencen_id'];
	    $documento_nota_credito_id 			= 	$request['documento_nota_credito_id'];
	    $regla_id 							= 	$request['reglas_id'];
		$reglas_id 							= 	explode(',', $regla_id);
		$notacredito            			=   new NotaCredito();

		$documentonotacredito 				= 	WEBDocumentoNotaCredito::where('id','=',$documento_nota_credito_id)->first();
		$response 						= 	$notacredito->nota_credito_cerrada($documento_nota_credito_id);
		if($response){return Redirect::back()->withInput()->with('errorbd', 'Nota de credito ('.$documentonotacredito->codigo.') se encuentra en estado cerrado');}


		$documentoasociado 					= 	WEBDocumentoAsociados::where('documento_nota_credito_id','=',$documento_nota_credito_id)
												->where('orden_id','=',$ordencen_id)
												->where('documento_id','=',$documento_id)
												->first();

		// desactivar la orden asociada
		$documentoasociado            	 	=	WEBDocumentoAsociados::find($documentoasociado->id);											
		$documentoasociado->activo 			=  	0;
		$documentoasociado->save();





		$documentonotacredito            	=	WEBDocumentoNotaCredito::find($documentonotacredito->id);
		$documentonotacredito->total_factura=  	$documentonotacredito->total_factura - $documentoasociado->total_factura;
		$documentonotacredito->total_reglas =  	$documentonotacredito->total_reglas - $documentoasociado->total_reglas;
		$documentonotacredito->save();



		WEBDetalleDocumentoAsociados::where('documento_asociados_id','=',$documentoasociado->id)
									->update(['activo' => 0]);


		$array_orden_detalle 				= 	WEBDetalleDocumentoAsociados::where('documento_asociados_id','=',$documentoasociado->id)       
												->pluck('ordendetallereglas_id')
                                        		->toArray();

		WEBOrdenDetalleRegla::whereIn('id',$array_orden_detalle)
									->update(['proceso_id' => 'OV' , 'documento_nota_credito_id' => '']);




	}







	public function actionVerAsignacionNotaCredito($idopcion,$iddocumentonotacredito)
	{


		$notacredito                    =   new NotaCredito();

		$iddocumentonotacredito 		= 	$this->funciones->decodificarmaestra($iddocumentonotacredito);
		$documentonotacredito 			=  	WEBDocumentoNotaCredito::where('id','=',$iddocumentonotacredito)->first();

		$documentoasociados				=  	WEBDocumentoAsociados::where('documento_nota_credito_id','=',$iddocumentonotacredito)
											->where('activo','=',1)
											->pluck('orden_id')
											->toArray();

		$regla_id						=  	WEBDetalleDocumentoAsociados::join('WEB.documento_asociados', 'WEB.documento_asociados.id', '=', 'WEB.detalle_documento_asociados.documento_asociados_id')
											->where('WEB.documento_asociados.documento_nota_credito_id','=',$iddocumentonotacredito)
	                                        ->where('WEB.detalle_documento_asociados.activo','=',1)
	                                        ->where('WEB.documento_asociados.activo','=',1)
											->pluck('regla_id')
											->toArray();


		$cuenta_id 						=  	$documentonotacredito->contrato_id;

        $lista_ordenes           		=   CMPOrden::where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
		                                    //->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									        ->where(function ($query){
							                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
							                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
											})
		                                    ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                                    ->where('CMP.ORDEN.IND_MATERIAL_SERVICIO','=','M')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_MODULO','=','MSI0000000000010')
		                                    ->whereIn('CMP.ORDEN.COD_ORDEN',$documentoasociados)->get();

		$direccion 						= 	$notacredito->direccion_cuenta($cuenta_id);
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();

		$funcion 						= 	$this;



		return View::make('notacredito/verasociacion',
						 [
							'lista_ordenes'   		=> $lista_ordenes,
						 	'funcion' 				=> $funcion,
						 	'notacredito' 			=> $notacredito,
							'regla_id' 				=> $regla_id,
						 	'direccion'	 			=> $direccion,
						 	'contrato'	 			=> $contrato,
						 	'documentonotacredito'  => $documentonotacredito,
						 	'idopcion'  			=> $idopcion,

						 ]);



	}




	public function actionAsociarNotaCredito($idopcion,$iddocumentonotacredito)
	{


		$notacredito                    =   new NotaCredito();



		$iddocumentonotacredito 		= 	$this->funciones->decodificarmaestra($iddocumentonotacredito);
		$documentonotacredito 			=  	WEBDocumentoNotaCredito::where('id','=',$iddocumentonotacredito)->first();

		$response 						= 	$notacredito->nota_credito_cerrada($iddocumentonotacredito);
		if($response){return Redirect::back()->withInput()->with('errorbd', 'Nota de credito ('.$documentonotacredito->codigo.') se encuentra en estado cerrado');}


		$documentoasociados				=  	WEBDocumentoAsociados::where('documento_nota_credito_id','=',$iddocumentonotacredito)
											->where('activo','=',1)
											->pluck('orden_id')
											->toArray();

		$regla_id						=  	WEBDetalleDocumentoAsociados::join('WEB.documento_asociados', 'WEB.documento_asociados.id', '=', 'WEB.detalle_documento_asociados.documento_asociados_id')
											->where('WEB.documento_asociados.documento_nota_credito_id','=',$iddocumentonotacredito)
	                                        ->where('WEB.detalle_documento_asociados.activo','=',1)
	                                        ->where('WEB.documento_asociados.activo','=',1)
											->pluck('regla_id')
											->toArray();


		$cuenta_id 						=  	$documentonotacredito->contrato_id;

        $lista_ordenes           		=   CMPOrden::where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
		                                    //->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									        ->where(function ($query){
							                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
							                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
											})
		                                    ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                                    ->where('CMP.ORDEN.IND_MATERIAL_SERVICIO','=','M')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_MODULO','=','MSI0000000000010')
		                                    ->whereIn('CMP.ORDEN.COD_ORDEN',$documentoasociados)->get();

		$direccion 						= 	$notacredito->direccion_cuenta($cuenta_id);
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();


		$combo_series 					= 	$notacredito->combo_series();



		$combo_motivos 					= 	$notacredito->combo_motivos_documento('TDO0000000000007');


		$funcion 						= 	$this;



		return View::make('notacredito/asociarfactura',
						 [
							'lista_ordenes'   		=> $lista_ordenes,
						 	'funcion' 				=> $funcion,
						 	'notacredito' 			=> $notacredito,
							'regla_id' 				=> $regla_id,
						 	'direccion'	 			=> $direccion,
						 	'contrato'	 			=> $contrato,
						 	'combo_series'	 		=> $combo_series,
						 	'combo_motivos'	 		=> $combo_motivos,
						 	'documentonotacredito'  => $documentonotacredito,
						 	'idopcion'  			=> $idopcion,

						 ]);



	}







	public function actionListarNotaCreditoAutoservicio($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listadocumentonotacredito 		= 	WEBDocumentoNotaCredito::where('empresa_id','=',Session::get('empresas')->COD_EMPR)
	    									->where('txt_modulo','=','AUTOSERVICIOS')
	    									->get();

		$funcion 						= 	$this;
		$notacredito                    =   new NotaCredito();

		return View::make('notacredito/notacreditoautoservicio',
						 [
						 	'idopcion' 								=> $idopcion,
						 	'listadocumentonotacredito' 			=> $listadocumentonotacredito,
						 	'funcion' 								=> $funcion,
						 	'notacredito' 							=> $notacredito,
						 ]);

	}


	public function actionAgregarReglaOrdenCen($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		if($_POST)
		{


			set_time_limit(0);
			$contrato_id 				=  	$request['contrato_id'];
			$facturasnotacredito 		=  	json_decode($request['facturasnotacredito'], true);
			$reglas_id 					= 	explode(',', $request['reglas']);
			$notacredito                =   new NotaCredito();


			/****************** REGISTRO DE CABECERA *********************/
			$codigo 					= 	$this->funciones->generar_codigo('WEB.documento_nota_credito',6);
			$idcabecera 				= 	$this->funciones->getCreateIdMaestra('WEB.documento_nota_credito');
			$cabecera            	 	=	new WEBDocumentoNotaCredito;
			$cabecera->id 	    		=  	$idcabecera;
			$cabecera->contrato_id 	    =  	$contrato_id;
			$cabecera->nota_credito_id 	=  	'';
			$cabecera->estado 	    	=  	'EM';
			$cabecera->codigo 	     	=  	$codigo;
			$cabecera->fecha_crea 	    =  	$this->fechaactual;
			$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
            $cabecera->txt_modulo       =   'AUTOSERVICIOS';
			$cabecera->save();


			/****************** REGISTRO DE DETALLE FACTURAS *********************/


				$totalfactura 					=   0.0000;
				$totalreglas 					=   0.0000;


				foreach($facturasnotacredito as $obj){

					$documento_id 								=	$obj['documento_id'];
					$orden_id 									=	$obj['orden_id'];

					$iddetalle 									= 	$this->funciones->getCreateIdMaestra('WEB.documento_asociados');
	            	$documento 	    							=   CMPDocumentoCtble::where('COD_DOCUMENTO_CTBLE','=',$documento_id)->first();

	            	$totalfactura 								=	$totalfactura + $documento->CAN_TOTAL;
	            	$totalreglas 								=	$totalreglas + $notacredito->monto_descuento_nota_credito_factura($documento_id,'',$reglas_id,$orden_id);

					$detalle            	 					=	new WEBDocumentoAsociados;
					$detalle->id 	    						=  	$iddetalle;
					$detalle->total_factura 					=  	$documento->CAN_TOTAL;
					$detalle->total_reglas 						=  	$notacredito->monto_descuento_nota_credito_factura($documento_id,'',$reglas_id,$orden_id);
					$detalle->documento_nota_credito_id 		=  	$idcabecera;

					$detalle->orden_id 							=  	$orden_id;
					$detalle->documento_id 						=  	$documento_id;
					$detalle->fecha_crea 	    				=  	$this->fechaactual;
					$detalle->usuario_crea 						=  	Session::get('usuario')->id;
					$detalle->empresa_id 						=   Session::get('empresas')->COD_EMPR;
					$detalle->centro_id 						=   Session::get('centros')->COD_CENTRO;
					$detalle->save();


					/****************** REGISTRO DE DETALLE REGLAS *********************/

			        $lista_productos    	=   CMPDetalleProducto::where('CMP.DETALLE_PRODUCTO.COD_ESTADO','=',1)
			                                    ->where('CMP.DETALLE_PRODUCTO.COD_TABLA','=',$documento_id)->get();

					foreach ($reglas_id as &$regla_id) {

			            foreach($lista_productos as $index => $item){
			    

			            	$iddetaller 								= 	$this->funciones->getCreateIdMaestra('WEB.detalle_documento_asociados');
			            	$total_regla          						=   $notacredito->monto_descuento_nc_documentocontable_producto_individual($documento_id,'',$item->COD_PRODUCTO,$orden_id,$regla_id);

			            	$ordendetallereglas 						= 	WEBOrdenDetalleRegla::where('estado','=','OC')
			            													->where('orden_id','=',$orden_id)
			            													->where('producto_id','=',$item->COD_PRODUCTO)
			            													->where('activo','=',1)
			            													->where('regla_id','=',$regla_id)
			            													->first();

			            	if($total_regla>0){

								$detaller            	 					=	new WEBDetalleDocumentoAsociados;
								$detaller->id 	    						=  	$iddetaller;
								$detaller->total_producto 					=  	$item->CAN_VALOR_VTA;
								$detaller->total_reglas 					=  	$total_regla;
								$detaller->fecha_crea 	    				=  	$this->fechaactual;
								$detaller->usuario_crea 					=  	Session::get('usuario')->id;
								$detaller->documento_asociados_id 			=  	$iddetalle;
								$detaller->documento_id 					=  	$documento_id;
								$detaller->producto_id 						=  	$item->COD_PRODUCTO;
								$detaller->regla_id 						=  	$regla_id;
								$detaller->ordendetallereglas_id 			=  	$ordendetallereglas->id;
                        		$detaller->cantidad                   		=   $item->CAN_PRODUCTO;
                        		$detaller->precio                     		=   $item->CAN_PRECIO_UNIT;
								$detaller->empresa_id 						=   Session::get('empresas')->COD_EMPR;
								$detaller->centro_id 						=   Session::get('centros')->COD_CENTRO;
								$detaller->save();

								$ordendetallereglas->proceso_id 					=  	'NC';
								$ordendetallereglas->save();



			            	}
			            }

					}

				}



			$cabeceram            	 		 =	WEBDocumentoNotaCredito::find($idcabecera);
			$cabeceram->total_factura 	     =  $totalfactura;
			$cabeceram->total_reglas 	 	 =  $totalreglas;			
			$cabeceram->save();


 			return Redirect::to('/gestion-de-nota-credito-autoservicios/'.$idopcion)->with('bienhecho', 'Reglas asociadas lote('.$codigo.') registrado con exito');


		}else{
                                   

			$comboclientes				= 	$this->funciones->combo_clientes_cuenta();
			$comboreglas  				= 	array('' => "Seleccione reglas");

			return View::make('notacredito/agregarreglaordencen',
							 [
							 	'idopcion' 			=> $idopcion,
								'comboclientes' 	=> $comboclientes,
								'comboreglas' 		=> $comboreglas,						
								'inicio'			=> $this->inicio,
								'hoy'				=> $this->fin,
							 ]);
		}
	}








	public function actionAjaxReglasClienteFechas(Request $request)
	{

		$cuenta_id 						=  	$request['cuenta_id'];
		$fechainicio 					=  	$request['fechainicio'];		
		$fechafin 						=  	$request['fechafin'];

		$notacredito                    =   new NotaCredito();
		$comboreglas					= 	$notacredito->combo_reglas_nc_cliente_fechas($fechainicio,$fechafin,$cuenta_id);


		return View::make('notacredito/ajax/comboreglas',
						 [
						 	'comboreglas' => $comboreglas
						 ]);

	}






	public function actionListarNotaCredito($idopcion)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		$comboclientes				= 	$this->funciones->combo_clientes_cuenta();

		return View::make('notacredito/notacredito',
						 [
						 	'idopcion' 			=> $idopcion,
							'comboclientes' 	=> $comboclientes,							
							'inicio'			=> $this->inicio,
							'hoy'				=> $this->fin,
						 ]);

	}


	public function actionAjaxGlosaDocumento(Request $request)
	{

	    $documento_id 			= 	$request['documento_id'];
		$notacredito            =   new NotaCredito();
		$documento 				= 	$notacredito->documento_atributos($documento_id);
	    print_r($documento->TXT_GLOSA);

	}




	public function actionAjaxListaOrdenCenNotaCredito(Request $request)
	{

		set_time_limit(0);

		$notacredito                    =   new NotaCredito();
		$cuenta_id 						=  	$request['cuenta_id'];
		$fechainicio 					=  	date_format(date_create($request['fechainicio']), 'Y-m-d');		
		$fechafin 						=  	date_format(date_create($request['fechafin']), 'Y-m-d');

		if(is_array($request['regla_id'])){
			$regla_id 						=  	$request['regla_id'];
		}else{

			$array_reglas_id 				= 	explode(',', $request['regla_id']);
			$regla_id 						=  	$array_reglas_id;
		}

		$idopcion 						=  	$request['opcion'];



        $array_orden_cen           		=   WEBOrdenDetalleRegla::join('CMP.ORDEN', 'CMP.ORDEN.COD_ORDEN', '=', 'WEB.ordendetallereglas.orden_id')
        									->where('WEB.ordendetallereglas.estado','=','OC')
	                                        ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
	                                        ->where('WEB.ordendetallereglas.activo','=',1)
	                                        ->whereRaw('Convert(varchar(10), WEB.ordendetallereglas.fecha_crea, 120) >= ?', [$fechainicio])
	                                        ->whereRaw('Convert(varchar(10), WEB.ordendetallereglas.fecha_crea, 120) <= ?', [$fechafin])
	                                        ->where('WEB.ordendetallereglas.activo','=',1)
	                                        ->whereIn('regla_id',$regla_id)
	                                        ->whereIn('proceso_id',['OV','OC'])
	                                        ->groupBy('orden_id')
	                                        ->pluck('orden_id')
	                                        ->toArray();


        $lista_ordenes           		=   CMPOrden::where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
		                                    //->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									        ->where(function ($query){
							                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
							                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
											})
		                                    ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                                    ->where('CMP.ORDEN.FEC_ORDEN','>=', $fechainicio)
		                                    ->where('CMP.ORDEN.FEC_ORDEN','<=', $fechafin)

		                                    ->where('CMP.ORDEN.IND_MATERIAL_SERVICIO','=','M')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_MODULO','=','MSI0000000000010')
		                                    ->whereIn('CMP.ORDEN.COD_ORDEN',$array_orden_cen)->get();


		$funcion 						= 	$this;
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();

		return View::make('notacredito/ajax/oredencen',
						 [
							'lista_ordenes'   		=> $lista_ordenes,
						 	'funcion' 				=> $funcion,
						 	'contrato' 				=> $contrato,
						 	'notacredito' 			=> $notacredito,
						 	'regla_id' 				=> $regla_id,
						 	'fechainicio' 			=> $fechainicio,
						 	'fechafin' 				=> $fechafin,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);

	}



	public function actionAjaxListaDetalleOrdenCenNotaCredito(Request $request)
	{

		set_time_limit(0);

		$notacredito                    =   new NotaCredito();
		$cuenta_id 						=  	$request['cuenta_id'];
		$fechainicio 					=  	date_format(date_create($request['fechainicio']), 'Y-m-d');		
		$fechafin 						=  	date_format(date_create($request['fechafin']), 'Y-m-d');

		if(is_array($request['regla_id'])){
			$regla_id 						=  	$request['regla_id'];
		}else{

			$array_reglas_id 				= 	explode(',', $request['regla_id']);
			$regla_id 						=  	$array_reglas_id;
		}

		$idopcion 						=  	$request['opcion'];



        $array_orden_cen           		=   WEBOrdenDetalleRegla::join('CMP.ORDEN', 'CMP.ORDEN.COD_ORDEN', '=', 'WEB.ordendetallereglas.orden_id')
        									->where('WEB.ordendetallereglas.estado','=','OC')
	                                        ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
	                                        ->where('WEB.ordendetallereglas.activo','=',1)
	                                        ->whereRaw('Convert(varchar(10), WEB.ordendetallereglas.fecha_crea, 120) >= ?', [$fechainicio])
	                                        ->whereRaw('Convert(varchar(10), WEB.ordendetallereglas.fecha_crea, 120) <= ?', [$fechafin])
	                                        ->where('WEB.ordendetallereglas.activo','=',1)
	                                        ->whereIn('regla_id',$regla_id)
	                                        ->whereIn('proceso_id',['OV','OC'])
	                                        ->groupBy('orden_id')
	                                        ->pluck('orden_id')
	                                        ->toArray();


        $lista_ordenes           		=   CMPOrden::where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
		                                    //->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									        ->where(function ($query){
							                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
							                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
											})
		                                    ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                                    ->where('CMP.ORDEN.FEC_ORDEN','>=', $fechainicio)
		                                    ->where('CMP.ORDEN.FEC_ORDEN','<=', $fechafin)

		                                    ->where('CMP.ORDEN.IND_MATERIAL_SERVICIO','=','M')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
		                                    ->where('CMP.ORDEN.COD_CATEGORIA_MODULO','=','MSI0000000000010')
		                                    ->whereIn('CMP.ORDEN.COD_ORDEN',$array_orden_cen)->get();


		$funcion 						= 	$this;
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();

		return View::make('notacredito/ajax/oredencendetalle',
						 [
							'lista_ordenes'   		=> $lista_ordenes,
						 	'funcion' 				=> $funcion,
						 	'contrato' 				=> $contrato,
						 	'notacredito' 			=> $notacredito,
						 	'regla_id' 				=> $regla_id,
						 	'fechainicio' 			=> $fechainicio,
						 	'fechafin' 				=> $fechafin,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);

	}





	public function actionAjaxListaFacturaNotaCredito(Request $request)
	{

		set_time_limit(0);

		$notacredito                    =   new NotaCredito();
		$cuenta_id 						=  	$request['cuenta_id'];
		$fechainicio 					=  	$request['fechainicio'];		
		$fechafin 						=  	$request['fechafin'];


		$listadocumentos 				= 	CMPDocumentoCtble::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
											->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
											->where('COD_CONTRATO_RECEPTOR','=',$cuenta_id)
											->where('COD_CATEGORIA_TIPO_DOC','=','TDO0000000000001')
											->where('COD_CATEGORIA_MONEDA','=','MON0000000000001')
					    					->where('FEC_EMISION','>=', $fechainicio)
					    					->where('FEC_EMISION','<=', $fechafin)
											->where('IND_MATERIAL_SERVICIO','=','M')
											->where('IND_COMPRA_VENTA','=','V')
											->where('COD_CATEGORIA_ESTADO_DOC_CTBLE','=','EDC0000000000003')
											->orderBy('FEC_EMISION', 'desc')
											->orderBy('COD_DOCUMENTO_CTBLE', 'desc')
											//->where('COD_CATEGORIA_MODULO','=','MSI0000000000010') //modulo
											->get();

		$direccion 						= 	$notacredito->direccion_cuenta($cuenta_id);
		$contrato 						= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id)->first();				
		$combo_series 					= 	$notacredito->combo_series();
		$combo_motivos 					= 	$notacredito->combo_motivos_documento('TDO0000000000007');

		//dd($notacredito->numero_documento('F005','TDO0000000000007'));



		$funcion 						= 	$this;

		return View::make('notacredito/ajax/facturas',
						 [
							'listadocumentos'   	=> $listadocumentos,
						 	'funcion' 				=> $funcion,
						 	'notacredito' 			=> $notacredito,
						 	'direccion'	 			=> $direccion,
						 	'contrato'	 			=> $contrato,
						 	'combo_series'	 		=> $combo_series,
						 	'combo_motivos'	 		=> $combo_motivos,
						 ]);

	}




	public function actionAjaxModalDetalleDocumento(Request $request)
	{


	    $contrato_id 			= 	$request['contrato_id'];
	    $documento_id 			= 	$request['documento_id'];
	    $referencia_id 			= 	$request['referencia_id'];
	    $ordencen_id 			= 	$request['ordencen_id'];
	    $regla_id 				= 	$request['reglas_id'];
	    $txt_reglas_id 			= 	$regla_id;
		$reglas_id 				= 	explode(',', $regla_id);


		$notacredito            =   new NotaCredito();

		$documento 		 		= 	CMPDocumentoCtble::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
									->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									->where('COD_DOCUMENTO_CTBLE','=',$documento_id)
									->first();


        // lista de descuento de la nota de credito
        $lista_productos    	=   CMPDetalleProducto::where('CMP.DETALLE_PRODUCTO.COD_ESTADO','=',1)
                                    ->where('CMP.DETALLE_PRODUCTO.COD_TABLA','=',$documento_id)->get();



		return View::make('notacredito/modal/ajax/detalledocumento',
						 [
						 	'contrato_id' 				=> $contrato_id,
						 	'documento_id' 				=> $documento_id,
						 	'referencia_id'	 			=> $referencia_id,
						 	'ordencen_id' 				=> $ordencen_id,
						 	'reglas_id'	 				=> $reglas_id,
						 	'txt_reglas_id'	 			=> $txt_reglas_id,
						 	'documento'	 				=> $documento,
						 	'lista_productos'	 		=> $lista_productos,
						 	'notacredito' 				=> $notacredito,					 	
						 ]);
	}



	public function actionAjaxNroDocumento(Request $request)
	{


		$notacredito    =   new NotaCredito();
		$serie 			=  	$request['serie'];
		return 			$notacredito->numero_documento($serie,'TDO0000000000007');

	}


	public function actionGuardarNotaCredito(Request $request)
	{


		if($_POST)
		{


			$osiris 							= 	new Osiris();
			$notacredito            			=   new NotaCredito();

			$contrato_id 						=  	$request['contrato_id'];
			$direccion_id 						=  	$request['direccion_id'];
			$idopcion 							=  	$request['idopcion'];

			$serie 								=  	$request['serie'];
			$motivo_id 							=  	$request['motivo_id'];
			$glosa 								=  	$request['glosa'];
			$informacionadicional 				=  	$request['informacionadicional'];
			$documentonotacredito_id 			=  	$request['documentonotacredito_id'];

			$facturasrelacionada 				=  	json_decode($request['facturasrelacionada'], true);
	        $listaid  							= 	array();
	        $i   								= 	0;
 
			$msjarray  							=	array();
	        $conts   							= 	0;
	        $contw								= 	0;
			$contd								= 	0;

			$documentonotacredito 				=  	WEBDocumentoAsociados::where('documento_nota_credito_id','=',$documentonotacredito_id)
													->where('activo','=',1)
													->get();

			/******* TOTAL NOTA DE CREDITO **********/
			$totalnotacredito 					=   0.0000;


			$regla_id							=  	WEBDetalleDocumentoAsociados::join('WEB.documento_asociados', 'WEB.documento_asociados.id', '=', 'WEB.detalle_documento_asociados.documento_asociados_id')
													->where('WEB.detalle_documento_asociados.activo','=',1)
													->where('WEB.documento_asociados.documento_nota_credito_id','=',$documentonotacredito_id)
													->pluck('regla_id')
													->toArray();

            foreach($documentonotacredito as $index => $item){

				$documento_id 				=	$item->documento_id;
				$orden_id 					=	$item->orden_id;
				$listaid[$i] 				=   $item->documento_id;
				$i= $i +1;
              	$totalnotacredito           =   $totalnotacredito + (float)$notacredito->monto_descuento_nota_credito_factura($documento_id,'',$regla_id,$orden_id);

            }

			$totalnotacredito 				=	$totalnotacredito;

			/*************************************/

			/******* DOCUMENTO RELACIONADO **********/
			$documento_relacionado_id 		=   '';
			foreach($facturasrelacionada as $obj){
				$documento_relacionado_id 	=	$obj['documento_id'];
			}
			/*************************************/


			$numero_documento 					=  	$notacredito->numero_documento($serie,'TDO0000000000007');
			$funcion 							= 	$this;
			$facturasnotacredito 				= 	"";

			$respuesta 							=  	$osiris->guardar_nota_credito($contrato_id,$direccion_id,$serie,$motivo_id,$glosa,$informacionadicional,$numero_documento,$funcion,$facturasnotacredito,$facturasrelacionada,$totalnotacredito,$documento_relacionado_id,$listaid,$notacredito,$documentonotacredito_id);



	    	$msjarray[] 			= 	array(	"data_0" => $respuesta, 
	    									"data_1" => 'aceptado a osiris', 
	    									"tipo" => 'S');
	    	$conts 					= 	1;


			/************** MENSAJES DEL DETALLE PEDIDO  ******************/
	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'nota de credito aceptados', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => $contw, 
	    						"data_1" => 'nota de credito rechazados', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => $contd, 
	    						"data_1" => 'nota de credito errados', 
	    						"tipo" => 'TD');


			$msjjson = json_encode($msjarray);


			return Redirect::to('/gestion-de-nota-credito-autoservicios/'.$idopcion)->with('xmlmsj', $msjjson);


		
		}


	}





}
