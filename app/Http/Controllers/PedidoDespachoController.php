<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente;
use View;
use Session;
use App\Biblioteca\Osiris;
use App\Biblioteca\Funcion;
use PDO;
use Mail;
use PDF;
use App\WEBOrdenDespacho;
  
class PedidoDespachoController extends Controller
{

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





			return View::make('despacho/crearordenpedidodespacho',
							 [
							 	'idopcion' 			=> $idopcion,
								'comboclientes' 	=> $comboclientes,						
								'inicio'			=> $this->inicio,
								'hoy'				=> $this->fin,
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
