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
use App\WEBOrdenDespacho,App\WEBDetalleOrdenDespacho,App\CMPOrden,App\WEBListaCliente,App\ALMProducto,App\CMPCategoria;
  
class AtenderPedidoDespachoController extends Controller
{


	public function actionAjaxPedidoAtenderModificarFechaEntrega(Request $request)
	{


		$array_data_producto_despacho 				= 	$request['data_producto_despacho'];
		$fechadeentrega 							=   date_format(date_create($request['fechadeentrega']), 'd-m-Y');
		$ordendespacho_id 							= 	$request['ordendespacho_id'];

		foreach($array_data_producto_despacho as $key => $obj){

			$detalle_orden_despacho_id				= 	$obj['data_detalle_orden_despacho'];
			$detalleordendespacho               	=   WEBDetalleOrdenDespacho::where('id','=',$detalle_orden_despacho_id)->first();
			$detalleordendespacho->fecha_entrega 	=  	$fechadeentrega;//fecha entrega falta
			$detalleordendespacho->save();
		}	

	    $ordendespacho 								=   WEBOrdenDespacho::where('id','=',$ordendespacho_id)->first();
		$funcion 									= 	$this;

		return View::make('despacho/ajax/alistapedidoatendertransferencia',
						 [
						 	'ordendespacho' 			=> $ordendespacho,
						 	'funcion' 					=> $funcion,
						 	'ajax'   		  			=> true,
						 ]);
	}



	public function actionAjaxModalAgregarProductosPedidoAtender(Request $request)
	{

		$data_producto 							= 	$request['data_producto'];
		$ordendespacho_id 						= 	$request['ordendespacho_id'];
		$detalleordendespacho               	=   WEBDetalleOrdenDespacho::where('id','=',$ordendespacho_id)->first();


		foreach($data_producto as $obj){

		    $producto_id 						= 	$obj['producto_id'];
		    $cantidad_atender 					= 	$obj['cantidad_atender'];

		    $producto 							= 	ALMProducto::where('COD_PRODUCTO','=',$producto_id)->first();

			$iddetalleordendespacho				= 	$this->funciones->getCreateIdMaestra('WEB.detalleordendespachos');
			$detalle            	 			=	new WEBDetalleOrdenDespacho;
			$detalle->id 	     	 			=  	$iddetalleordendespacho;
			$detalle->ordendespacho_id 			=  	$ordendespacho_id;
			$detalle->nro_orden_cen 			=  	'';
			$detalle->fecha_pedido 				=  	$this->fecha_sin_hora; 
			$detalle->fecha_entrega 			=  	$this->fecha_sin_hora;//fecha entrega falta
			$detalle->muestra 					=  	0.0000;
			$detalle->cantidad 					=  	0.0000;
			$detalle->cantidad_atender 			=  	$cantidad_atender;
			$detalle->modulo 					=  	'atender_pedido';
			$detalle->kilos 					=  	0.0000;
			$detalle->cantidad_sacos 			=  	0.0000;
			$detalle->palets 					=  	0.0000;
			$detalle->presentacion_producto 	=  	$producto->CAN_PESO_MATERIAL;
			$detalle->grupo 					=  	0;
			$detalle->grupo_orden 				=  	0;
			$detalle->grupo_movil 				=  	0;
			$detalle->grupo_orden_movil 		=  	0;
			$detalle->correlativo 				=  	$detalle->correlativo + 1;
			$detalle->tipo_grupo_oc 			=  	'';
			$detalle->fecha_crea 	 			=   $this->fechaactual;
			$detalle->usuario_crea 				=   Session::get('usuario')->id;
			$detalle->unidad_medida_id 			=  	$producto->COD_CATEGORIA_UNIDAD_MEDIDA;
			$detalle->cliente_id 				=  	'';
			$detalle->orden_id 					=  	'';
			$detalle->producto_id 				=  	$producto->COD_PRODUCTO;
			$detalle->empresa_id 				=   Session::get('empresas')->COD_EMPR;
			$detalle->centro_id 				=   Session::get('centros')->COD_CENTRO;
			$detalle->save();

		}

	    $ordendespacho 							=   WEBOrdenDespacho::where('id','=',$ordendespacho_id)->first();
		$funcion 								= 	$this;

		return View::make('despacho/ajax/alistapedidoatendertransferencia',
						 [
						 	'ordendespacho' 			=> $ordendespacho,
						 	'funcion' 					=> $funcion,
						 	'ajax'   		  			=> true,
						 ]);

	}




	public function actionAjaxModalListaOrdenAtenderProducto(Request $request)
	{


		$ordendespacho_id 				= 	$request['ordendespacho_id'];
	    $ordendespacho 					=   WEBOrdenDespacho::where('id','=',$ordendespacho_id)->first();
	    $listaproductos 				= 	DB::table('WEB.LISTAPRODUCTOSAVENDER')
	    									->whereIn('COD_CATEGORIA_UNIDAD_MEDIDA',['UME0000000000001','UME0000000000013'])
				    					 	->orderBy('NOM_PRODUCTO', 'asc')
				    					 	->get();
		$funcion 						= 	$this;


		return View::make('despacho/modal/ajax/lproducto',
						 [
						 	'ordendespacho_id' 			=> $ordendespacho_id,
						 	'ordendespacho' 			=> $ordendespacho,
						 	'listaproductos' 			=> $listaproductos,
						 	'funcion' 					=> $funcion,
						 	'ajax' 						=> true,
						 ]);


	}


	public function actionAjaxAjaxModificarCantidadAtenderProducto(Request $request)
	{


		$catidad_atender 								= 	(float)$request['catidad_atender'];
		$detalle_orden_despacho_id 						= 	$request['detalle_orden_despacho_id'];

		$detalle_orden_despacho_id 						=   WEBDetalleOrdenDespacho::where('id','=',$detalle_orden_despacho_id)->first();
		$detalle_orden_despacho_id->cantidad_atender 	=   $catidad_atender;
		$detalle_orden_despacho_id->save();

	    $ordendespacho 									=   WEBOrdenDespacho::where('id','=',$detalle_orden_despacho_id->ordendespacho_id)->first();
		$funcion 										= 	$this;


		return View::make('despacho/ajax/alistapedidoatendertransferencia',
						 [
						 	'ordendespacho' 			=> $ordendespacho,
						 	'funcion' 					=> $funcion,
						 	'ajax'   		  			=> true,
						 ]);
	}



	public function actionAtenderOrdenDespacho($idopcion,$idordendespacho)
	{

		$idordendespacho 	= 	$this->funciones->decodificarmaestra($idordendespacho);
	    $ordendespacho 		=   WEBOrdenDespacho::where('id','=',$idordendespacho)->first();
		$funcion 			= 	$this;


		return View::make('despacho/atenderordendespacho',
						 [
						 	'ordendespacho' 						=> $ordendespacho,
						 	'funcion' 								=> $funcion,
						 	'idopcion' 								=> $idopcion,
						 ]);

	}


	public function actionAjaxListaAtenderPedidosDespacho(Request $request)
	{



		$fechainicio 					=  	$request['fechainicio'];
		$fechafin 						=  	$request['fechafin'];
		$idopcion 						=  	$request['opcion_id'];


	    $listaordenatender 				=   WEBOrdenDespacho::join('CMP.CATEGORIA','CMP.CATEGORIA.COD_CATEGORIA','=','WEB.ordendespachos.estado_id')
	    									->where('fecha_orden','>=', $fechainicio)
	    									->where('fecha_orden','<=', $fechafin)
	    									->orderBy('fecha_crea', 'desc')
	    									->get();
		$funcion 						= 	$this;

		return View::make('despacho/ajax/alistarpedidoatender',
						 [
						 	'listaordenatender' 					=> $listaordenatender,
						 	'funcion' 								=> $funcion,
						 	'idopcion' 								=> $idopcion,

						 	'ajax' 									=> true,
						 ]);

	}





	public function actionListarAtenderPedido($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

		$fechainicio 					=  	$this->fecha_menos_quince;
		$fechafin 						=  	$this->fin;

	    $listaordenatender 				=   WEBOrdenDespacho::join('CMP.CATEGORIA','CMP.CATEGORIA.COD_CATEGORIA','=','WEB.ordendespachos.estado_id')
	    									->where('fecha_orden','>=', $fechainicio)
	    									->where('fecha_orden','<=', $fechafin)
	    									->orderBy('fecha_crea', 'desc')
	    									->get();

		$funcion 						= 	$this;


		return View::make('despacho/listarordenatender',
						 [
						 	'idopcion' 								=> $idopcion,
						 	'listaordenatender' 					=> $listaordenatender,
						 	'funcion' 								=> $funcion,
						 	'fechainicio' 							=> $fechainicio,
						 	'fechafin' 								=> $fechafin,
						 ]);
	}

}
