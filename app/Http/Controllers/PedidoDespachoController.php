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
use App\WEBOrdenDespacho,App\CMPOrden,App\WEBListaCliente,App\ALMProducto,App\CMPCategoria;
  
class PedidoDespachoController extends Controller
{


	public function actionAjaxPedidoEliminarFila(Request $request)
	{

		$array_detalle_producto_request 	= 	json_decode($request['array_detalle_producto'],true);
		$array_detalle_producto 			=	array();
		$grupo 								= 	(int)$request['grupo'];
		$correlativo 						= 	(int)$request['correlativo'];
		$fila 								= 	$request['fila'];

		$disminuir 							= 	0;
		$grupo_oc							= 	"";
		$orden_cen							= 	"";


		//eliminar la fila del array
		foreach ($array_detalle_producto_request as $key => $item) {
            if((int)$item['correlativo'] == $fila) {
                unset($array_detalle_producto_request[$key]);

                //guardamos para luego disminuir
				if($item['tipo_grupo_oc'] == 'oc_grupo'){	
					$disminuir 	= 	1;
					$grupo_oc 	= 	$item['grupo'];
					$orden_cen 	= 	$item['orden_cen'];			
				}

            }
		}


		if($disminuir>0){	
			// dismuir la cantidad de rowspan
			foreach ($array_detalle_producto_request as $key => $item) {
		        if($item['grupo'] == $grupo_oc && $item['orden_cen'] == $orden_cen) {
		        	$array_detalle_producto_request[$key]['grupo_orden'] = (int)$array_detalle_producto_request[$key]['grupo_orden'] -1;
		        }
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




	public function actionAjaxModalAgregarProductosPedido(Request $request)
	{

		$data_producto 						= 	$request['data_producto'];
		$grupo 								= 	(int)$request['grupo'];
		$correlativo 						= 	(int)$request['correlativo'];
		$cuenta_id_m 						= 	$request['cuenta_id_m'];
		$cliente 							= 	WEBListaCliente::where('COD_CONTRATO','=',$cuenta_id_m)->first();
		$array_detalle_producto_request 	= 	json_decode($request['array_detalle_producto'],true);
		$array_detalle_producto 			=	array();
		$rowspan 							= 	0;

		foreach($data_producto as $obj){

		    $producto_id 					= 	$obj['producto_id'];
		    $producto 						= 	ALMProducto::where('COD_PRODUCTO','=',$producto_id)->first();
		    $unidad_medida 					= 	CMPCategoria::where('COD_CATEGORIA','=',$producto->COD_CATEGORIA_UNIDAD_MEDIDA)->first();


			$array_nuevo_producto 			=	array();
			$grupo 							= 	$grupo + 1;


			$correlativo 					= 	$correlativo + 1;
			$array_nuevo_producto 			=	array(
												"empresa_cliente_id" 		=> $cliente->id,
												"empresa_cliente_nombre" 	=> $cliente->NOM_EMPR,
												"orden_id" 					=> "",
												"orden_cen" 				=> "",
												"fecha_pedido" 				=> $this->fin,
												"fecha_entrega" 			=> $this->fin,
									            "producto_id" 				=> $producto->COD_PRODUCTO,
									            "nombre_producto" 			=> $producto->NOM_PRODUCTO,
									            "unidad_medida_id" 			=> $producto->COD_CATEGORIA_UNIDAD_MEDIDA,
									            "nombre_unidad_medida" 		=> $unidad_medida->NOM_CATEGORIA,
									            "cantidad" 					=> "0",
									            "grupo" 					=> $grupo,
									            "grupo_orden" 				=> "1",
									            "grupo_movil" 				=> '0',
									            "grupo_orden_movil" 		=> '0',
									            "correlativo" 				=> $correlativo,
										        "tipo_grupo_oc" 			=> "oc_individual"
									        	);

			$rowspan 						= 	$rowspan + 1;
			array_push($array_detalle_producto,$array_nuevo_producto);



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

	public function actionAjaxModalAgregarOrdenCenPedido(Request $request)
	{

		$data_orden_cen 					= 	$request['data_orden_cen'];
		$grupo 								= 	(int)$request['grupo'];
		$correlativo 						= 	(int)$request['correlativo'];
		$tipo_grupo 						= 	$request['tipo_grupo'];

		$array_detalle_producto_request 	= 	json_decode($request['array_detalle_producto'],true);
		$array_detalle_producto 			=	array();




		foreach($data_orden_cen as $obj){

		    $ordencen_id 					= 	$obj['ordencen_id'];
		    $orden 							= 	CMPOrden::where('COD_ORDEN','=',$ordencen_id)->first();
			$lista_detalle_ordencen			= 	$this->funciones->lista_orden_cen_detalle($ordencen_id);
			$array_nuevo_producto 			=	array();
			if($tipo_grupo == 'oc_grupo'){	$grupo 	= 	$grupo + 1;	}	
			$rowspan 						= 	0;


			while($row = $lista_detalle_ordencen->fetch())
			{

				if($tipo_grupo == 'oc_individual'){	$grupo 	= 	$grupo + 1;	}

				$unidad_medida 				= 	CMPCategoria::where('COD_CATEGORIA','=',$row['COD_CATEGORIA_UNIDAD_MEDIDA'])->first();
				$correlativo 				= 	$correlativo + 1;


				//calculo de kilos,cantidad_sacos,palets
				$producto 					= 	ALMProducto::where('COD_PRODUCTO','=',$row['COD_PRODUCTO'])->first();
				$kilos 						=   $row['CAN_PRODUCTO']*$producto->CAN_PESO_MATERIAL;
				$cantidad_sacos				= 	$row['CAN_PRODUCTO']/$producto->CAN_BOLSA_SACO;
				$palets 					= 	$cantidad_sacos/$producto->CAN_SACO_PALET;
				//



				$array_nuevo_producto		= 	

				$this->funciones->llenar_array_productos($orden->COD_EMPR_CLIENTE,$orden->TXT_EMPR_CLIENTE,$row['COD_TABLA'],$orden->NRO_ORDEN_CEN,$this->fin,
								$this->fin,$row['COD_PRODUCTO'],$row['TXT_NOMBRE_PRODUCTO'],$row['COD_CATEGORIA_UNIDAD_MEDIDA'],$unidad_medida->NOM_CATEGORIA,
								$row['CAN_PRODUCTO'],$kilos,$cantidad_sacos,$palets,$grupo,'0','0','0',$correlativo,$tipo_grupo,$producto->CAN_PESO_MATERIAL);


				$rowspan 	= 	$rowspan + 1;
				array_push($array_detalle_producto,$array_nuevo_producto);

			}

			// modificar un valor en array
			if($tipo_grupo == 'oc_grupo'){
				$array_detalle_producto = $this->funciones->modificarmultidimensionalarray($array_detalle_producto,'grupo_orden',$rowspan,$orden->NRO_ORDEN_CEN);
			}else{
				$array_detalle_producto = $this->funciones->modificar_individual_multidimensionalarray($array_detalle_producto,'grupo_orden');
			}

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
		$combotipogrupo					= 	array('oc_grupo' => "Grupo",'oc_individual' => "Individual"); 	


		return View::make('despacho/modal/ajax/ordencenproducto',
						 [
						 	'cuenta_id' 				=> $cuenta_id,
						 	'listaproductos' 			=> $listaproductos,
						 	'listaordencen' 			=> $listaordencen,
						 	'funcion' 					=> $funcion,
						 	'combotipogrupo' 			=> $combotipogrupo,
						 	'ajax' 						=> true,
						 ]);


	}




//actionAjaxModalAgregarProductosOrdenCen




}
