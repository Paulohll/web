<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBRegla,App\WEBListaCliente,App\WEBReglaProductoCliente,App\WEBPrecioProductoContrato,App\WEBPrecioProductoContratoHistorial;
use View;
use Session;
use Hashids;


class GestionProductoController extends Controller
{


	//18-10-2019
	public function actionGestionMasivaPrecioProducto($idopcion)
	{

		//combo vendedores por centro
		$combo_jefes_ventas 				= 	$this->funciones->combo_jefe_ventas();

		$combo_cliente  					= 	array('' => "Seleccione Cliente");
		$combo_canal  						= 	array('' => "Seleccione Canal");
		$combo_sub_canal  					= 	array('' => "Seleccione Sub Canal");
		$combo_lista_productos_todos 		= 	$this->funciones->combo_lista_productos();
		$combo_lista_empresas 				= 	$this->funciones->combo_lista_empresa();

		return View::make('catalogo/gestion/masiva',
						 [
						 	'combo_jefes_ventas' 				=> $combo_jefes_ventas,
						 	'combo_canal' 						=> $combo_canal,						 	
						 	'combo_sub_canal' 					=> $combo_sub_canal,
						 	'combo_lista_productos_todos' 		=> $combo_lista_productos_todos,
						 	'combo_lista_empresas' 				=> $combo_lista_empresas,
						 	'combo_cliente' 					=> $combo_cliente,				 	
						 	'idopcion' 							=> $idopcion,
						 ]);

	}







	//18-10-2019
	public function actionAjaxListaPrecioProductoMasiva(Request $request)
	{

		$responsable_id 			=  	$request['responsable_id'];
		$canal_id 					=  	$request['canal_id'];
		$cliente_id 				=  	$request['cliente_id'];
		$subcanal_id 				=  	$request['subcanal_id'];
		$producto_id 				=  	$request['producto_id'];
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

		$funcion 					= 	$this;


		return View::make('catalogo/gestion/ajax/listaproductomasiva',
						 [
						 	'listacliente' 					=> $listacliente,
						 	'listadeproductos' 				=> $listadeproductos,
						 	'funcion' 						=> $funcion
						 ]);



	}

	//18-10-2019
	public function actionAjaxActualizarPrecioProductoMasivas(Request $request)
	{
		
		

		$respuesta 					= 	json_decode($request['datastring'], true);
		$precio 					=  	$request['precio_total'];
		$mensaje 					=  	count($respuesta).' Precios asignado con exito';
		$response[] = array(
			'error'           		=> false,
			'mensaje'      			=> $mensaje
		);



		foreach($respuesta as $obj){

		    $producto_id 				= 	$obj['producto_id'];
		    $cliente_id 				= 	$obj['cliente_id'];
		    $contrato_id 				= 	$obj['contrato_id'];
		    $empresa_id 				= 	$obj['empresa_id'];

		    $this->funciones->asignar_precio_estandar_producto_empresa($empresa_id,$producto_id,$precio);

			$precioproducto             =   WEBPrecioProductoContrato::where('producto_id','=',$producto_id)
											->where('contrato_id','=',$contrato_id)
											->where('empresa_id','=',$empresa_id)
											//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->first();

			if(count($precioproducto)>0){

				/****** MODIFICAR PRECIO PRODUCTO **********/
				
				$cabecera            	 	=	WEBPrecioProductoContrato::find($precioproducto->id);;
				$cabecera->precio 	     	=   $precio;
				$cabecera->fecha_mod 	 	=   $this->fechaactual;
				$cabecera->usuario_mod 		=   Session::get('usuario')->id;
				$cabecera->save();

				/****** AGREGAR PRECIO PRODUCTO HOSTORIAL **********/
				$idprecioproductohistorial 				=  	$this->funciones->getCreateIdMaestra('WEB.precioproductocontratohistoriales');
				$cabecera            	 				=	new WEBPrecioProductoContratoHistorial;
				$cabecera->id 	     	 				=   $idprecioproductohistorial;
				$cabecera->precio 	     				=   $precioproducto->precio;
				$cabecera->fecha_crea 	 				=   $this->fechaactual;
				$cabecera->usuario_crea 				=   $precioproducto->usuario_crea;
				$cabecera->precioproductocontrato_id 	= 	$precioproducto->id;
				$cabecera->producto_id 	 				= 	$precioproducto->producto_id;
				$cabecera->empresa_id 					=   $precioproducto->empresa_id;
				$cabecera->centro_id 					=   $precioproducto->centro_id;
				$cabecera->contrato_id 					=   $precioproducto->contrato_id;			
				$cabecera->save();

			}else{

				/****** AGREGAR PRECIO PRODUCTO **********/
				$idprecioproducto 			=  	$this->funciones->getCreateIdMaestra('WEB.precioproductocontratos');
				$cabecera            	 	=	new WEBPrecioProductoContrato;
				$cabecera->id 	     	 	=   $idprecioproducto;
				$cabecera->precio 	     	=   $precio;
				$cabecera->fecha_crea 	 	=   $this->fechaactual;
				$cabecera->usuario_crea 	=   Session::get('usuario')->id;
				$cabecera->producto_id 	 	= 	$producto_id;
				$cabecera->empresa_id 		=   $empresa_id;
				$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
				$cabecera->contrato_id 		=   $contrato_id;			
				$cabecera->save();

			}

		}

		echo json_encode($response);


	}



}
