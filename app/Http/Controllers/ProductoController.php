<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\CMPCategoria,App\WEBPrecioProducto,App\WEBPrecioProductoHistorial,App\WEBRegla;
use View;
use Session;
use Hashids;
Use Nexmo;
use Keygen;


class ProductoController extends Controller
{



	public function actionListaReglasDescuento(Request $request)
	{

		$estado_id 					=  	$request['estado_id'];
		$fechainicio 				=  	$request['fechainicio'];
		$fechafin 					=  	$request['fechafin'];
		$idopcion 					=  	$request['idopcion'];


	    $listaprecio 				= 	WEBRegla::orderBy('fechafin', 'asc')
					    				->whereIn('tiporegla', ['POV', 'PNC'])
					    				//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->where('estado','=',$estado_id)
				    					->where('fechainicio','>=', $fechainicio)
				    					->where('fechainicio','<=', $fechafin)
					    				->get();
		$funcion 					= 	$this;
		$fechavacia  				= 	$this->fechavacia;



		return View::make('regla/ajax/listareglasdescuento',
						 [
						 	'listaprecio' 			=> $listaprecio,
						 	'funcion' 				=> $funcion,
						 	'fechavacia'	 		=> $fechavacia,
							'ajax'   		  		=> true,
						 	'idopcion'	 			=> $idopcion,					 	
						 ]);


	}



	public function actionPrecioProducto($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $departamentos 		= 	DB::table('CMP.CATEGORIA')
	    					 	->where('TXT_GRUPO','=','DEPARTAMENTO')
	    					 	->orderBy('NOM_CATEGORIA', 'asc')->get();

	    $cod_empresa 		= 	Session::get('empresas')->COD_EMPR;
	    $cod_centro 		= 	Session::get('centros')->COD_CENTRO;

	    $productos 			= 	DB::table('WEB.LISTAPRODUCTOSAVENDER')
	    						->leftjoin('WEB.precioproductos', function($join) use($cod_empresa, $cod_centro){
					                $join->on('WEB.LISTAPRODUCTOSAVENDER.COD_PRODUCTO','=','WEB.precioproductos.producto_id')
					                	 ->where('WEB.precioproductos.empresa_id','=',$cod_empresa)
					                	 ->where('WEB.precioproductos.centro_id','=',$cod_centro);
					            })
	    					 	->orderBy('NOM_PRODUCTO', 'asc')
	    					 	->get();


		return View::make('catalogo/precioproducto',
						 [
						 	'departamentos' => $departamentos,
						 	'productos' 	=> $productos,
						 	'idopcion' 		=> $idopcion,
						 ]);

	}


	public function actionAjaxGuardarPrecioProducto(Request $request)
	{

		$precio 					=  	$request['precio'];
		$producto_id 				=  	$request['producto_id'];
		$producto_pre 				=  	$request['producto_pre'];
		$producto_id 				=  	$this->funciones->decodificarid($producto_id,$producto_pre);

		$precioproducto             =   WEBPrecioProducto::where('producto_id','=',$producto_id)
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();

		if(count($precioproducto)>0){

			/****** MODIFICAR PRECIO PRODUCTO **********/
			
			$cabecera            	 	=	WEBPrecioProducto::find($precioproducto->id);;
			$cabecera->precio 	     	=   $precio;
			$cabecera->fecha_mod 	 	=   $this->fechaactual;
			$cabecera->usuario_mod 		=   Session::get('usuario')->id;
			$cabecera->save();

			/****** AGREGAR PRECIO PRODUCTO HOSTORIAL **********/
			$idprecioproductohistorial 	=  	$this->funciones->getCreateIdMaestra('WEB.precioproductohistoriales');
			$cabecera            	 	=	new WEBPrecioProductoHistorial;
			$cabecera->id 	     	 	=   $idprecioproductohistorial;
			$cabecera->precio 	     	=   $precioproducto->precio;
			$cabecera->fecha_crea 	 	=   $this->fechaactual;
			$cabecera->usuario_crea 	=   $precioproducto->usuario_crea;
			$cabecera->precioproducto_id= 	$precioproducto->id;
			$cabecera->producto_id 	 	= 	$precioproducto->producto_id;
			$cabecera->empresa_id 		=   $precioproducto->empresa_id;
			$cabecera->centro_id 		=   $precioproducto->centro_id;
			$cabecera->save();

		}else{

			/****** AGREGAR PRECIO PRODUCTO **********/
			$idprecioproducto 			=  	$this->funciones->getCreateIdMaestra('WEB.precioproductos');
			$cabecera            	 	=	new WEBPrecioProducto;
			$cabecera->id 	     	 	=   $idprecioproducto;
			$cabecera->precio 	     	=   $precio;
			$cabecera->fecha_crea 	 	=   $this->fechaactual;
			$cabecera->usuario_crea 	=   Session::get('usuario')->id;
			$cabecera->producto_id 	 	= 	$producto_id;
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->save();

		}

		echo("Precio guardado con exito");

	}



	public function actionListarReglaPrecioRegular($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listaprecioregular = WEBRegla::orderBy('fechafin', 'asc')
	    					->where('tiporegla','=','PRD')
	    					//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
	    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
	    					->get();

		$fechavacia  				= $this->fechavacia;
		$funcion 					= 	$this;	
		return View::make('regla/listaprecioregular',
						 [
						 	'listaprecioregular' 	=> $listaprecioregular,
						 	'fechavacia'	 		=> $fechavacia,
						 	'funcion' 				=> 		$funcion,						 	
						 	'idopcion' 				=> $idopcion,
						 ]);
	}




	public function actionAgregarReglaPrecioRegular($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}

	    /******************************************************/

		if($_POST)
		{

			$codigo 					= 	$this->funciones->generar_codigo('WEB.reglas',6);
			$idregla 					= 	$this->funciones->getCreateIdMaestra('WEB.reglas');

			$cabecera            	 	=	new WEBRegla;
			$cabecera->id 	     	 	=  	$idregla;
			$cabecera->codigo 	    	=  	$codigo;
			$cabecera->tiporegla 	    =  	'PRD';
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->fechainicio 	    =  	$this->fechaactual;
			$cabecera->cantidadminima 	=  	0;
			$cabecera->tipodescuento 	=  	'IMP';
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	'PU';
			$cabecera->departamento_id 	=  	trim($request['departamento']);			
			$cabecera->fecha_crea 	    =  	$this->fechaactual;
			$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->save();
 
 			return Redirect::to('/gestion-de-regla-de-precio-regular/'.$idopcion)->with('bienhecho', 'Precio regular '.$request['nombre'].' registrado con exito');

		}else{


			$funcion 					= 	$this;	
		    //combo departamentos
			$combodepartamentos 		= 	$this->funciones->combo_departamentos();

			return View::make('regla/agregarprecioregular',
						[				
						  	'idopcion'  					=> 		$idopcion,
						 	'funcion' 				 		=> 		$funcion,
						 	'combodepartamentos'			=> 		$combodepartamentos,						 							  	
						]);
		}
	}


	public function actionModificarPrecioRegular($idopcion,$idregla,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idregla = $this->funciones->decodificarmaestra($idregla);

		if($_POST)
		{

			$cabecera            	 	=	WEBRegla::find($idregla);
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	trim($request['estado']);
			$cabecera->fecha_mod 	    =  	$this->fechaactual;
			$cabecera->usuario_mod 		=  	Session::get('usuario')->id;
			$cabecera->save();
 
 			return Redirect::to('/gestion-de-regla-de-negociacion/'.$idopcion)->with('bienhecho', 'Descuento '.$request['nombre'].' modificado con éxito');


		}else{


				$regla = WEBRegla::where('id', $idregla)->first();
				$fechavacia  = $this->fechavacia;

			    //combo departamentos
				$combodepartamentos 		= 	$this->funciones->combo_departamentos_modificar(trim($regla->departamento_id));

		        return View::make('regla/modificarprecioregular', 
		        				[
		        					'regla'  		=> $regla,
									'fechavacia'  	=> $fechavacia,
						  			'idopcion' 		=> $idopcion,
						 			'combodepartamentos'			=> 		$combodepartamentos,						  			
		        				]);
		}
	}














	public function actionListarReglaNegociacion($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listanegociacion = WEBRegla::orderBy('fechafin', 'asc')
	    					->where('tiporegla','=','NEG')
	    					//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
	    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
	    					->get();

		$fechavacia  = $this->fechavacia;
		$funcion 	 = 	$this;

		return View::make('regla/listanegociacion',
						 [
						 	'listanegociacion' 		=> $listanegociacion,
						 	'fechavacia'	 		=> $fechavacia,
						 	'funcion'	 			=> $funcion,
						 	'idopcion' 				=> $idopcion,
						 ]);
	}


	public function actionAgregarNegociacion($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}

	    /******************************************************/

		if($_POST)
		{


			$departamento_id 			=   $request['departamento'];
			if(is_null($departamento_id)){$departamento_id = '';}


			$codigo 					= 	$this->funciones->generar_codigo('WEB.reglas',6);
			$idregla 					= 	$this->funciones->getCreateIdMaestra('WEB.reglas');

			$cabecera            	 	=	new WEBRegla;
			$cabecera->id 	     	 	=  	$idregla;
			$cabecera->codigo 	    	=  	$codigo;
			$cabecera->tiporegla 	    =  	'NEG';
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->tipodescuento 	=  	'IMP';
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	'PU';
			$cabecera->descuentoaumento =  	'DS';
			$cabecera->departamento_id 	=  	$departamento_id;
			$cabecera->fecha_crea 	    =  	$this->fechaactual;
			$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->save();


 			return Redirect::to('/gestion-de-regla-de-negociacion/'.$idopcion)->with('bienhecho', 'Negociación '.$request['nombre'].' registrado con exito');


		}else{

			$fechaactual = $this->fechaactualinput;
			$fechavacia  = $this->fechavacia;
		    //combo departamentos
			$combodepartamentos 		= 	$this->funciones->combo_departamentos();
			$combocondicionpago		= 	$this->funciones->combo_condicionpago();

			return View::make('regla/agregarnegociacion',
						[				
							'fechaactual'  			=> $fechaactual,
							'fechavacia'  			=> $fechavacia,
						 	'combodepartamentos'	=> $combodepartamentos,
							'idopcion'  			=> $idopcion,
							'combocondicionpago'    => $combocondicionpago
						]);
		}
	}


	public function actionModificarNegociacion($idopcion,$idregla,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idregla = $this->funciones->decodificarmaestra($idregla);

		if($_POST)
		{

			$cabecera            	 	=	WEBRegla::find($idregla);
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	trim($request['estado']);
			$cabecera->fecha_mod 	    =  	$this->fechaactual;
			$cabecera->usuario_mod 		=  	Session::get('usuario')->id;
			$cabecera->save();
 
 			return Redirect::to('/gestion-de-regla-de-negociacion/'.$idopcion)->with('bienhecho', 'Descuento '.$request['nombre'].' modificado con éxito');


		}else{


				$regla = WEBRegla::where('id', $idregla)->first();
				$fechavacia  = $this->fechavacia;
	
		        return View::make('regla/modificarnegociacion', 
		        				[
		        					'regla'  		=> $regla,
									'fechavacia'  	=> $fechavacia,
						  			'idopcion' 		=> $idopcion
		        				]);
		}
	}





	public function actionListarReglaPrecio($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/


	    $fechainicio  		= 	$this->fecha_menos_treinta_dias;
	    $fechafin  			= 	$this->fin;


	    $listaprecio 		= 	WEBRegla::orderBy('fechafin', 'asc')
			    				->whereIn('tiporegla', ['POV', 'PNC'])
			    				//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
								->where('centro_id','=',Session::get('centros')->COD_CENTRO)
								->where('estado','=','PU')
		    					->where('fechainicio','>=', $fechainicio)
		    					->where('fechainicio','<=', $fechafin)
			    				->get();


		$comboestado        =   array('' => "Seleccione el tipo de pago",'CU' => "CERRADO",'PU' => "PUBLICADO");

		$fechavacia  		= 	$this->fechavacia;
		$funcion 			= 	$this;

		return View::make('regla/listaprecios',
						 [
						 	'listaprecio' 			=> $listaprecio,
						 	'fechavacia'	 		=> $fechavacia,					 	
						 	'idopcion' 				=> $idopcion,
						 	'funcion' 				=> $funcion,
						 	'fechainicio' 			=> $fechainicio,
						 	'fechafin' 				=> $fechafin,
						 	'comboestado' 			=> $comboestado,
						 	'ajax'   		  		=> false,						 	
						 ]);
	}


	public function actionAgregarPrecio($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}

	    /******************************************************/

		if($_POST)
		{


			$departamento_id 			=   $request['departamento'];
			$condicionpago_id 			=   $request['condicionpago'];
			if(is_null($departamento_id)){$departamento_id = '';}
			if(is_null($condicionpago_id)){$condicionpago_id = '';}

			$codigo 					= 	$this->funciones->generar_codigo('WEB.reglas',6);
			$idregla 					= 	$this->funciones->getCreateIdMaestra('WEB.reglas');

			$documento 					=   trim($request['documento']);
			$cabecera            	 	=	new WEBRegla;
			$cabecera->id 	     	 	=  	$idregla;
			$cabecera->codigo 	    	=  	$codigo;
			$cabecera->tiporegla 	    =  	'P'.$documento;
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->cantidadmaxima 	=  	trim($request['cantidadmaxima']);
			$cabecera->tipodescuento 	=  	trim($request['tipodescuento']);
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	'PU';
			$cabecera->documento 		=  	trim($request['documento']);
			$cabecera->descuentoaumento =  	'DS';
			$cabecera->departamento_id 	=  	$departamento_id;
			$cabecera->condicionpago_id =  	$condicionpago_id;
			$cabecera->fecha_crea 	    =  	$this->fechaactual;
			$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->save();
 

 			return Redirect::to('/gestion-de-regla-de-precio-producto/'.$idopcion)->with('bienhecho', 'Regla '.$request['nombre'].' registrado con exito');


		}else{

			$fechaactual = $this->fechaactualinput;
			$fechavacia  = $this->fechavacia;

		    //combo departamentos
			$combodepartamentos 		= 	$this->funciones->combo_departamentos();
			$combocondicionpago		= 	$this->funciones->combo_condicionpago();

			return View::make('regla/agregarprecio',
						[				
							'fechaactual'  			=> 		$fechaactual,
							'fechavacia'  			=> 		$fechavacia,	
						 	'combodepartamentos'	=> 		$combodepartamentos,								
							  'idopcion'  			=> 		$idopcion,
							  'combocondicionpago'  =>      $combocondicionpago
						]);
		}
	}


	public function actionModificarPrecio($idopcion,$idregla,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idregla = $this->funciones->decodificarmaestra($idregla);

		if($_POST)
		{

			$cabecera            	 	=	WEBRegla::find($idregla);
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->tipodescuento 	=  	trim($request['tipodescuento']);
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	trim($request['estado']);
			$cabecera->documento 		=  	trim($request['documento']);
			$cabecera->fecha_mod 	    =  	$this->fechaactual;
			$cabecera->usuario_mod 		=  	Session::get('usuario')->id;
			$cabecera->save();
 
 			return Redirect::to('/gestion-de-regla-de-precio-producto/'.$idopcion)->with('bienhecho', 'Descuento '.$request['nombre'].' modificado con éxito');


		}else{


				$regla = WEBRegla::where('id', $idregla)->first();
				$fechavacia  = $this->fechavacia;
	
		        return View::make('regla/modificarprecio', 
		        				[
		        					'regla'  		=> $regla,
									'fechavacia'  	=> $fechavacia,
						  			'idopcion' 		=> $idopcion
		        				]);
		}
	}




	public function actionListarReglaCupones($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/

	    $listacupones = WEBRegla::orderBy('fechafin', 'asc')
	    				->where('tiporegla','=','CUP')
	    				//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
	    				->where('centro_id','=',Session::get('centros')->COD_CENTRO)
	    				->get();


		$fechavacia  	= $this->fechavacia;
		$funcion 		= 	$this;

		return View::make('regla/listacupones',
						 [
						 	'listacupones' 	=> $listacupones,
						 	'idopcion' 		=> $idopcion,
							'fechavacia'  	=> $fechavacia,
							'funcion'		=> $funcion,
						 ]);
	}

	public function actionAjaxGenerarCupon()
	{
		echo($this->funciones->codecupon());
	}


	public function actionAgregarCupon($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}

	    /******************************************************/

		if($_POST)
		{

			$departamento_id 			=   $request['departamento'];
			if(is_null($departamento_id)){$departamento_id = '';}

			$codigo 					= 	$this->funciones->generar_codigo('WEB.reglas',6);
			$idregla 					= 	$this->funciones->getCreateIdMaestra('WEB.reglas');

			$cabecera            	 	=	new WEBRegla;
			$cabecera->id 	     	 	=  	$idregla;
			$cabecera->codigo 	    	=  	$codigo;
			$cabecera->tiporegla 	    =  	'CUP';
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->descripcion 	    =  	trim($request['descripcion']);
			$cabecera->cupon 	     	=  	trim($request['cupon']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->totaldisponible 	=  	trim($request['totaldisponible']);
			$cabecera->totalcadacuenta 	=  	trim($request['totalcadacuenta']);
			$cabecera->tipodescuento 	=  	trim($request['tipodescuento']);
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	'PU';
			$cabecera->descuentoaumento =  	'DS';
			$cabecera->departamento_id 	=  	$departamento_id;
			$cabecera->fecha_crea 	    =  	$this->fechaactual;
			$cabecera->usuario_crea 	=  	Session::get('usuario')->id;
			$cabecera->empresa_id 		=   Session::get('empresas')->COD_EMPR;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->save();
 

 			return Redirect::to('/gestion-de-regla-de-cupon-producto/'.$idopcion)->with('bienhecho', 'Cupón '.$request['nombre'].' registrado con exito');


		}else{

			$fechaactual = $this->fechaactualinput;
			$fechavacia  = $this->fechavacia;

		    //combo departamentos
			$combodepartamentos 		= 	$this->funciones->combo_departamentos();

			return View::make('regla/agregarcupon',
						[				
							'fechaactual'  		=> $fechaactual,
							'fechavacia'  		=> $fechavacia,
							'combodepartamentos'	=> 		$combodepartamentos,
						  	'idopcion'  		=> $idopcion,
						]);
		}
	}



	public function actionModificarCupon($idopcion,$idregla,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idregla = $this->funciones->decodificarmaestra($idregla);

		if($_POST)
		{

			$cabecera            	 	=	WEBRegla::find($idregla);
			$cabecera->nombre 	     	=  	trim($request['nombre']);
			$cabecera->descripcion 	    =  	trim($request['descripcion']);
			$cabecera->cupon 	     	=  	trim($request['cupon']);
			$cabecera->fechainicio 	    =  	trim($request['fechainicio']);
			$cabecera->fechafin 	    =  	trim($request['fechafin']);
			$cabecera->cantidadminima 	=  	trim($request['cantidadminima']);
			$cabecera->totaldisponible 	=  	trim($request['totaldisponible']);
			$cabecera->totalcadacuenta 	=  	trim($request['totalcadacuenta']);
			$cabecera->tipodescuento 	=  	trim($request['tipodescuento']);
			$cabecera->descuento 		=  	trim($request['descuento']);
			$cabecera->estado 			=  	trim($request['estado']);
			$cabecera->fecha_mod 	    =  	$this->fechaactual;
			$cabecera->usuario_mod 		=  	Session::get('usuario')->id;
			$cabecera->save();
 
 			return Redirect::to('/gestion-de-regla-de-cupon-producto/'.$idopcion)->with('bienhecho', 'Cupón '.$request['nombre'].' modificado con éxito');


		}else{


				$regla = WEBRegla::where('id', $idregla)->first();
				$fechavacia  = $this->fechavacia;
	
		        return View::make('regla/modificarcupon', 
		        				[
		        					'regla'  		=> $regla,
									'fechavacia'  		=> $fechavacia,
						  			'idopcion' 		=> $idopcion
		        				]);
		}
	}



}
/*Nexmo::message()->send([
    'to'   => '51979529813',
    'from' => '51979820173',
    'text' => 'Mensaje desde laravel'
]);*/