<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBRegla,App\WEBListaCliente,App\WEBReglaProductoCliente;
use View;
use Session;
use Hashids;


class GestionReglaController extends Controller
{


	public function actionGestionMasivaReglaPrecio($idopcion,$idregla)
	{

		//combo vendedores por centro
		$combo_jefes_ventas 				= 	$this->funciones->combo_jefe_ventas();

		$combo_cliente  					= 	array('' => "Seleccione Cliente");
		$combo_canal  						= 	array('' => "Seleccione Canal");
		$combo_sub_canal  					= 	array('' => "Seleccione Sub Canal");
		$combo_lista_productos_todos 		= 	$this->funciones->combo_lista_productos_todos();
		$combo_lista_empresas 				= 	$this->funciones->combo_lista_empresa();


		$idregla 							= 	$this->funciones->decodificarmaestra($idregla);
		$regla 								=   WEBRegla::where('id','=',$idregla)->first();


		return View::make('regla/gestion/masiva',
						 [
						 	'combo_jefes_ventas' 				=> $combo_jefes_ventas,
						 	'combo_canal' 						=> $combo_canal,						 	
						 	'combo_sub_canal' 					=> $combo_sub_canal,
						 	'combo_lista_productos_todos' 		=> $combo_lista_productos_todos,
						 	'combo_lista_empresas' 				=> $combo_lista_empresas,
						 	'regla' 							=> $regla,
						 	'combo_cliente' 					=> $combo_cliente,				 	
						 	'idopcion' 							=> $idopcion,
						 ]);



	}

	//cambio
	public function actionAjaxListaContratoProductoMasiva(Request $request)
	{

		$responsable_id 			=  	$request['responsable_id'];
		$canal_id 					=  	$request['canal_id'];
		$cliente_id 				=  	$request['cliente_id'];
		$subcanal_id 				=  	$request['subcanal_id'];
		$producto_id 				=  	$request['producto_id'];
		$regla_id 					=  	$request['regla_id'];
		$empresa_id 				= 	$request['empresa_id'];
		

		if($responsable_id=='1'){
			// lista de clientes
		    $listacliente 				= 	WEBListaCliente::Canal($canal_id)
		    								->SubCanal($subcanal_id)
		    								->Cliente($cliente_id)
		    								->whereIn('COD_EMPR', $empresa_id)
						    				//->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
						    				->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
						    				->orderBy('NOM_EMPR', 'asc')
						    				->get();

		}else{
			// lista de clientes
		    $listacliente 				= 	WEBListaCliente::where('COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
		    								->Canal($canal_id)
		    								->SubCanal($subcanal_id)
		    								->Cliente($cliente_id)
		    								->whereIn('COD_EMPR', $empresa_id)
						    				//->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
						    				->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
						    				->orderBy('NOM_EMPR', 'asc')
						    				->get();
		}



	    // lista productos
	    $tipoprecio_id				= 	0; //todos
	    if($producto_id==1){$producto_id='';}
	    $listadeproductos 			= 	$this->funciones->lista_productos_precio_buscar($producto_id,$tipoprecio_id,'');


	    //	lista reglas asignadas
		$listareglaproductoclientes 		= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->select('WEB.reglaproductoclientes.id','producto_id','cliente_id','contrato_id','regla_id','nombre')
												->where('WEB.reglaproductoclientes.activo','=',1)
												->where('WEB.reglas.id','=',$regla_id)
												->get();

		$funcion 					= 	$this;

		return View::make('regla/gestion/ajax/listacontratomasiva',
						 [
						 	'listacliente' 					=> $listacliente,
						 	'listadeproductos' 				=> $listadeproductos,
						 	'listareglaproductoclientes' 	=> $listareglaproductoclientes,
						 	'funcion' 						=> $funcion,
						 ]);



	}


	public function actionAjaxEliminarReglasMasivas(Request $request)
	{
		
		$respuesta 					= 	json_decode($request['datastring'], true);
		$mensaje 					=  	count($respuesta).' Reglas eliminadas con exito';
		
		$response[] = array(
			'error'           		=> false,
			'mensaje'      			=> $mensaje
		);

		foreach($respuesta as $obj){

		    $producto_id 				= 	$obj['producto_id'];
		    $cliente_id 				= 	$obj['cliente_id'];
		    $contrato_id 				= 	$obj['contrato_id'];
		    $regla_id 					= 	$obj['regla_id'];

		    $reglaproductocliente		=	WEBReglaProductoCliente::where('producto_id','=',$producto_id)
		    								->where('cliente_id','=',$cliente_id)
		    								->where('contrato_id','=',$contrato_id)
		    								->where('activo','=','1')
		    								->where('regla_id','=',$regla_id)->first();

			$cabecera            	 	=	WEBReglaProductoCliente::find($reglaproductocliente->id);
			$cabecera->fecha_mod 	    =  	$this->fechaactual;
			$cabecera->usuario_mod 		=  	Session::get('usuario')->id;		
			$cabecera->activo 	 	 	=  	0;			 
			$cabecera->save();

		}

		echo json_encode($response);


	}




	public function actionAjaxActualizarReglasMasivas(Request $request)
	{
		
		$regla_id 					= 	$request['regla_id'];

		$respuesta 					= 	json_decode($request['datastring'], true);
		$mensaje 					=  	count($respuesta).' Reglas asignada con exito';
		$response 					= 	$this->funciones->la_regla_esta_desactivada($regla_id,$mensaje);
		if($response[0]['error']){echo json_encode($response); exit();}

		foreach($respuesta as $obj){

		    $producto_id 				= 	$obj['producto_id'];
		    $cliente_id 				= 	$obj['cliente_id'];
		    $contrato_id 				= 	$obj['contrato_id'];
		    $regla_id 					= 	$obj['regla_id'];

			$idreglaproductocliente 	= 	$this->funciones->getCreateIdMaestra('WEB.reglaproductoclientes');
			$cabecera            	 	=	new WEBReglaProductoCliente;
			$cabecera->id 	     	 	=  	$idreglaproductocliente;
			$cabecera->producto_id 	    =  	$producto_id;
			$cabecera->regla_id 	    =  	$regla_id;
			$cabecera->cliente_id 	    =  	$cliente_id;
			$cabecera->contrato_id 	    =  	$contrato_id;
			$cabecera->fecha_crea 	    =  	$this->fechaactual;
			$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
			$cabecera->save();

		}

		echo json_encode($response);


	}







}
