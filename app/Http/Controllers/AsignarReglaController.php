<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente,App\STDTipoDocumento,App\WEBReglaProductoCliente,App\WEBReglaCreditoCliente;
use App\WEBPrecioProductoContrato,App\WEBPrecioProductoContratoHistorial,App\WEBRegla;
use View;
use Session;
use PDO;

class AsignarReglaController extends Controller
{


	public function actionAjaxPrecioRegularDescuento(Request $request)
	{

		$producto_id 				=  	$request['producto_id'];
		$cliente_id 				=  	$request['cliente_id'];
		$contrato_id 				=  	$request['contrato_id'];
		$departamento_id 			=  	"";


		$empresa_id			= 	Session::get('empresas')->COD_EMPR;
		$centro_id			=	Session::get('centros')->COD_CENTRO;
		$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC web.precio_producto_contrato ?,?,?,?,?');
        $stmt->bindParam(1, $contrato_id ,PDO::PARAM_STR);
        $stmt->bindParam(2, $producto_id ,PDO::PARAM_STR);
        $stmt->bindParam(3, $departamento_id ,PDO::PARAM_STR);
        $stmt->bindParam(4, $empresa_id ,PDO::PARAM_STR);
        $stmt->bindParam(5, $centro_id ,PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch();



	   	$lista_precio_departamento 	= 	$this->funciones->lista_precios_departamento_cliente($contrato_id,$producto_id,$cliente_id);
	   	$lista_precio_departamento_cadena 	= implode("<br>", $lista_precio_departamento);

        echo('PRECIO REGULAR : S/. '.$resultado['precio'].'<br>'.$lista_precio_departamento_cadena);

	}

	public function actionAjaxCambiarEstadoContrato(Request $request)
	{

		$sw_contrato 				=  	$request['sw_contrato'];
		$producto_id 				=  	$request['producto_id'];
		$contrato_id 				=  	$request['contrato_id'];
				
		$cabecera             		=   WEBPrecioProductoContrato::where('producto_id','=',$producto_id)
										->where('contrato_id','=',$contrato_id)
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();

		$cabecera->ind_contrato 	=   $sw_contrato;
		$cabecera->save();

		echo("Contrato guardado con exito");

	}



	public function actionAjaxGuardarPrecioProductoContrato(Request $request)
	{

		$precio 					=  	$request['precio'];
		$producto_id 				=  	$request['producto_id'];
		$cliente_id 				=  	$request['cliente_id'];
		$contrato_id 				=  	$request['contrato_id'];				

		$precioproducto             =   WEBPrecioProductoContrato::where('producto_id','=',$producto_id)
										->where('contrato_id','=',$contrato_id)
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
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
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->contrato_id 		=   $contrato_id;			
			$cabecera->save();

		}

		echo("Precio guardado con exito");

	}




	public function actionListarClienteRegla($idopcion,Request $request)
	{


		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $cliente 			= $request['cliente_select'];
	    $producto_select 	= $request['producto_select'];
	    $tipoprecio_id 		= $request['tipoprecio_id'];


	    $respuestvacio 		= $this->funciones->respuestavacio($cliente,$producto_select);
	    $paginacion 		= 1;

	    // ingresa cuando no hay filtro
	    if($respuestvacio){

	    	// lista clientes
			$arrayidclientes 				= 	$this->funciones->array_id_clientes_top(100);

		    $listacliente 					= 	WEBListaCliente::name($cliente)
		    									->whereIn('id',$arrayidclientes)
		    									->orderBy('NOM_EMPR', 'asc')
						    					->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
						    					->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
		    									->paginate($paginacion);

	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio();
		    //combo clientes
	    	$combolistaclientes 			= 	$this->funciones->combo_nombres_lista_clientes();
		    //combo productos
	    	$combolistaproductos 			= 	$this->funciones->combo_nombres_lista_productos();

			$combotipoprecio_producto		= 	$this->funciones->combo_tipo_precio_productos_asignar();

	    }else{ // ingresa cuando si hay filtro

		    $clientes 						= 	WEBListaCliente::name($cliente)
						    					->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
						    					->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
												->first();

		    $listacliente 					= 	WEBListaCliente::name($cliente)
						    					->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
						    					->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
												->orderBy('NOM_EMPR', 'asc')
												->paginate($paginacion);
												
	    	// lista productos
	    	$listadeproductos 				= 	$this->funciones->lista_productos_precio_buscar($producto_select,$tipoprecio_id,$clientes->COD_CONTRATO);


		    //combo clientes
	    	$combolistaclientes 			= 	array($cliente => $cliente);
		    //combo productos
		    if($producto_select!=''){
	    		$combolistaproductos 		= 	array($producto_select => $this->funciones->nombre_producto_seleccionado($producto_select));		    	
		    }else{
		    	$combolistaproductos 		= 	$this->funciones->combo_nombres_lista_productos();
		    }

			$combotipoprecio_producto		= 	$this->funciones->combo_tipo_precio_productos_asignar();



	    }

	    //combo tipo documento
		$combotipodocumentoxclientes 		= 	$this->funciones->combotipodocumentoxclientes();

	    //array de todos las reglas asignado a un producto segun el cliente
		$listareglaproductoclientes 		= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->select('WEB.reglaproductoclientes.id','producto_id','cliente_id','contrato_id','regla_id','nombre')
												->where('WEB.reglaproductoclientes.activo','=',1)
												->get();

		$funcion 							= 	$this;

		// capturar request en la vista = {{ Request::query('cliente_select') }}



		return View::make('regla/asignarregla',
						 [
						 	'listacliente' 				 	=> $listacliente,
						 	'funcion' 				 		=> $funcion,						 	
						 	'listadeproductos' 				=> $listadeproductos,
						 	'listareglaproductoclientes' 	=> $listareglaproductoclientes,
						 	'combotipodocumentoxclientes' 	=> $combotipodocumentoxclientes,
						 	'combolistaclientes' 			=> $combolistaclientes,
						 	'combolistaproductos' 			=> $combolistaproductos,
							'combotipoprecio_producto' 		=> $combotipoprecio_producto,						 	
						 	'idopcion' 					 	=> $idopcion,
						 ]);

	}


	public function actionAjaxActualizarListaRegla(Request $request)
	{

	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $contrato_id 			= 	$request['contrato_id'];
	    $tipo 					= 	$request['tipo'];
	    $color 					= 	$request['color'];
	    //array de todos las reglas asignado a un producto segun el cliente
		$listareglaproductoclientes 		= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->select('WEB.reglaproductoclientes.id','producto_id','cliente_id','contrato_id','regla_id','nombre')
												->where('WEB.reglaproductoclientes.activo','=',1)
												->get();

		$funcion 							= 	$this;

		return View::make('regla/listado/ajax/etiquetas',
						 [
						 	'cliente_id' 					=> $cliente_id,
						 	'producto_id'	 				=> $producto_id,
						 	'contrato_id'	 				=> $contrato_id,
						 	'tipo'	 						=> $tipo,
						 	'color'	 						=> $color,
						 	'listareglaproductoclientes'	=> $listareglaproductoclientes,
						 	'funcion' 				 		=> $funcion,						 	
						 ]);
	}


	public function actionAjaxActualizarModalRegla(Request $request)
	{

	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $contrato_id 			= 	$request['contrato_id'];
	    $tipo 					= 	$request['tipo'];
	    $color 					= 	$request['color'];

		$listareglas 			= 	$this->funciones->reglas_actualizar_modal($producto_id,$cliente_id,$contrato_id,$tipo);

		$funcion 				= 	$this;

		return View::make('regla/modal/ajax/etiquetas',
						 [
						 	'listareglas' 					=> $listareglas,
						 	'color'	 						=> $color,
							'funcion' 				 		=> $funcion,						 	
						 ]);
	}




	public function actionAjaxActualizarListaReglaPrecioRegular(Request $request)
	{

	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $contrato_id 			= 	$request['contrato_id'];
	    $tipo 					= 	$request['tipo'];
	    $color 					= 	$request['color'];
	    //array de todos las reglas asignado a un producto segun el cliente
		$listareglaproductoclientes 		= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->select('WEB.reglaproductoclientes.id','producto_id','cliente_id','contrato_id','regla_id','nombre')
												->where('WEB.reglaproductoclientes.activo','=',1)
												->get();

		$funcion 							= 	$this;

		return View::make('regla/listado/ajax/listaprecioregular',
						 [
						 	'cliente_id' 					=> $cliente_id,
						 	'producto_id'	 				=> $producto_id,
						 	'contrato_id'	 				=> $contrato_id,
						 	'tipo'	 						=> $tipo,
						 	'color'	 						=> $color,
						 	'listareglaproductoclientes'	=> $listareglaproductoclientes,
						 	'funcion' 				 		=> $funcion,						 	
						 ]);
	}


	public function actionAjaxActualizarModalReglaPrecioRegular(Request $request)
	{

	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $contrato_id 			= 	$request['contrato_id'];
	    $tipo 					= 	$request['tipo'];
	    $color 					= 	$request['color'];

		$listareglas 			= 	$this->funciones->reglas_actualizar_modal($producto_id,$cliente_id,$contrato_id,$tipo);

		$funcion 				= 	$this;

		return View::make('regla/modal/ajax/precioregular',
						 [
						 	'listareglas' 					=> $listareglas,
						 	'color'	 						=> $color,
							'funcion' 				 		=> $funcion,						 	
						 ]);
	}







	public function actionAjaxModalDetalle(Request $request)
	{


	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $contrato_id 			= 	$request['contrato_id'];
	    $nombre 				= 	$request['nombre'];
	    $tipo 					= 	$request['tipo'];
	    $nombreselect 			= 	$request['nombreselect'];
	    $prefijo 				= 	$request['prefijo'];
	    $color 					= 	$request['color'];

		$cliente 				= 	WEBListaCliente::where('id','=',$cliente_id)->where('COD_CONTRATO','=',$contrato_id)->first();
		$producto 				= 	$this->funciones->producto_buscar($producto_id);
		$listareglas 			= 	$this->funciones->reglas_actualizar_modal($producto_id,$cliente_id,$contrato_id,$tipo);
    	$comboreglas 			= 	$this->funciones->combo_activas_regla_tipo($tipo,$nombreselect);
		$funcion 				= 	$this;

		return View::make('regla/modal/ajax/detalle',
						 [
						 	'cliente' 				=> $cliente,
						 	'contrato_id' 			=> $contrato_id,
						 	'producto'	 			=> $producto,
						 	'listareglas'	 		=> $listareglas,
						 	'comboreglas'	 		=> $comboreglas,
						 	'nombre'	 			=> $nombre,
						 	'tipo'	 				=> $tipo,
						 	'nombreselect'	 		=> $nombreselect,
						 	'prefijo'	 			=> $prefijo,
						 	'color'	 				=> $color,
							'funcion' 				=> $funcion,						 	
						 ]);
	}



	public function actionAjaxModalDetallePrecioRegular(Request $request)
	{


	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $contrato_id 			= 	$request['contrato_id'];
	    $nombre 				= 	$request['nombre'];
	    $tipo 					= 	$request['tipo'];
	    $nombreselect 			= 	$request['nombreselect'];
	    $prefijo 				= 	$request['prefijo'];
	    $color 					= 	$request['color'];

		$cliente 				= 	WEBListaCliente::where('id','=',$cliente_id)->where('COD_CONTRATO','=',$contrato_id)->first();
		$producto 				= 	$this->funciones->producto_buscar($producto_id);
		$listareglas 			= 	$this->funciones->reglas_actualizar_modal($producto_id,$cliente_id,$contrato_id,$tipo);
    	$comboreglas 			= 	$this->funciones->combo_activas_regla_tipo($tipo,$nombreselect);
		$funcion 				= 	$this;

	    //combo departamentos
		$combodepartamentos 	= 	$this->funciones->combo_departamentos();


		return View::make('regla/modal/ajax/detalleprecioregular',
						 [
						 	'cliente' 				=> $cliente,
						 	'contrato_id' 			=> $contrato_id,
						 	'producto'	 			=> $producto,
						 	'listareglas'	 		=> $listareglas,
						 	'comboreglas'	 		=> $comboreglas,
						 	'combodepartamentos'	=> $combodepartamentos,
						 	'nombre'	 			=> $nombre,
						 	'tipo'	 				=> $tipo,
						 	'nombreselect'	 		=> $nombreselect,
						 	'prefijo'	 			=> $prefijo,
						 	'color'	 				=> $color,
							'funcion' 				=> $funcion,						 	
						 ]);
	}








	public function actionAjaxDetalleRegla(Request $request)
	{

	    $producto_id 			= 	$request['producto_id'];
	    $cliente_id 			= 	$request['cliente_id'];
	    $regla_id 				= 	$request['regla_id'];

		$regla 					= 	$this->funciones->regla_buscar($regla_id);
		$producto 				= 	$this->funciones->producto_buscar($producto_id);
		$cliente 				= 	$this->funciones->cliente_buscar($cliente_id);
		$fechavacia  			= 	$this->fechavacia;

		$funcion 				= 	$this;	

		return View::make('regla/popover/ajax/detalleregla',
						 [
						 	'cliente' 				=> $cliente,
						 	'producto'	 			=> $producto,
						 	'regla'	 				=> $regla,
						 	'fechavacia'	 		=> $fechavacia,
						 	'funcion'	 			=> $funcion,
						 ]);

	}


	public function actionAjaxAgregarRegla(Request $request)
	{


	    $producto_id 				= 	$request['producto_id'];
	    $cliente_id 				= 	$request['cliente_id'];
	    $contrato_id 				= 	$request['contrato_id'];
	    $regla_id 					= 	$request['regla_id'];
	    $tipo 						= 	$request['tipo'];

		$idreglaproductocliente 	= 	$this->funciones->getCreateIdMaestra('WEB.reglaproductoclientes');
		$mensaje 					=  	'Regla asignada con exito';


		/*$response 						= 	$this->funciones->precio_regla_calculo_menor_cero($producto_id,$cliente_id,$mensaje,$tipo,$regla_id);
		if($response[0]['error']){echo json_encode($response); exit();}*/

		$response 						= 	$this->funciones->tiene_regla_activa($producto_id,$cliente_id,$contrato_id,$mensaje,$tipo);
		if($response[0]['error']){echo json_encode($response); exit();}

		$response 						= 	$this->funciones->tiene_regla_repetida($producto_id,$cliente_id,$contrato_id,$regla_id,$mensaje,$tipo);
		if($response[0]['error']){echo json_encode($response); exit();}


		$cabecera            	 	=	new WEBReglaProductoCliente;
		$cabecera->id 	     	 	=  	$idreglaproductocliente;
		$cabecera->producto_id 	    =  	$producto_id;
		$cabecera->regla_id 	    =  	$regla_id;
		$cabecera->cliente_id 	    =  	$cliente_id;
		$cabecera->contrato_id 	    =  	$contrato_id;
		$cabecera->fecha_crea 	    =  	$this->fechaactual;
		$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
		$cabecera->save();

		echo json_encode($response);
	}



	public function actionAjaxAgregarReglaPrecioRegular(Request $request)
	{


	    $producto_id 				= 	$request['producto_id'];
	    $cliente_id 				= 	$request['cliente_id'];
	    $contrato_id 				= 	$request['contrato_id'];
	    $departamento_id_pr 		= 	trim($request['departamento_id_pr']);
	    $descuento_pr 				= 	$request['descuento_pr'];
	    $tipo 						= 	$request['tipo'];
		$mensaje 					=  	'Regla asignada con exito';


		$response 					= 	$this->funciones->tiene_regla_repetida_departamento($producto_id,$cliente_id,$contrato_id,$departamento_id_pr,$mensaje,$tipo);
		if($response[0]['error']){echo json_encode($response); exit();}

		$cliente 					=	WEBListaCliente::where('COD_CONTRATO','=',$contrato_id)->first();


		/********* agregar regla nueva de precio regular *******/

		$codigo 					= 	$this->funciones->generar_codigo('WEB.reglas',6);
		$idregla 					= 	$this->funciones->getCreateIdMaestra('WEB.reglas');

		$cabecera            	 	=	new WEBRegla;
		$cabecera->id 	     	 	=  	$idregla;
		$cabecera->codigo 	    	=  	$codigo;
		$cabecera->tiporegla 	    =  	'PRD';
		$cabecera->nombre 	     	=  	$cliente->NOM_EMPR;
		$cabecera->fechainicio 	    =  	$this->fechaactual;
		$cabecera->cantidadminima 	=  	0;
		$cabecera->tipodescuento 	=  	'IMP';
		$cabecera->descuento 		=  	trim($request['descuento_pr']);
		$cabecera->estado 			=  	'PU';
		$cabecera->departamento_id 	=  	$request['departamento_id_pr'];			
		$cabecera->fecha_crea 	    =  	$this->fechaactual;
		$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
		$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
		$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
		$cabecera->save();


		$idreglaproductocliente 	= 	$this->funciones->getCreateIdMaestra('WEB.reglaproductoclientes');

		$cabecera            	 	=	new WEBReglaProductoCliente;
		$cabecera->id 	     	 	=  	$idreglaproductocliente;
		$cabecera->producto_id 	    =  	$producto_id;
		$cabecera->regla_id 	    =  	$idregla;
		$cabecera->cliente_id 	    =  	$cliente_id;
		$cabecera->contrato_id 	    =  	$contrato_id;
		$cabecera->fecha_crea 	    =  	$this->fechaactual;
		$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
		$cabecera->save();

		echo json_encode($response);
	}





	public function actionAjaxEliminarRegla(Request $request)
	{

	    $idreglaproductocliente 	= 	$request['idreglaproductocliente'];
		$mensaje 					=  	'Regla eliminada con exito';
		$error						=   false;

		$cabecera            	 	=	WEBReglaProductoCliente::find($idreglaproductocliente);
		$cabecera->fecha_mod 	    =  	$this->fechaactual;
		$cabecera->usuario_mod 		=  	Session::get('usuario')->id;		
		$cabecera->activo 	 	 	=  	0;			 
		$cabecera->save();


		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		echo json_encode($response);
	}



	public function ActualizarReglaCredito(Request $request)
	{
		if (!$request->ajax()) return redirect('/');


		$reglacredito = $request->reglacredito;
			if ($reglacredito!='') {

						
				$regla = WEBReglaCreditoCliente::findOrFail($reglacredito);
				$regla->canlimitecredito = $request->limitecredito;
				$regla->condicionpago_id = $request->condicionpago;
				$regla->clasificacion = $request->clasificacion;
				$regla->save();
				


				
			}else {
				
				$idreglacreditocliente 	= 	$this->funciones->getCreateIdMaestra('WEB.reglacreditoclientes');
				$regla =new WEBReglaCreditoCliente;
				$regla->id 	= $idreglacreditocliente;
				$regla->activo= 1;
				$regla->cliente_id=$request->cliente;
				$regla->canlimitecredito = $request->limitecredito;
				$regla->condicionpago_id = $request->condicionpago;
				$regla->clasificacion ='B';
				$regla->fecha_crea 	    =  	$this->fechaactual;
				$regla->usuario_crea 	=  	Session::get('usuario')->id;
				$regla->save();
				
			}

	    
	}



}
