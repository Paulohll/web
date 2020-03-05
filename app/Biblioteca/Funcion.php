<?php
namespace App\Biblioteca;

use Illuminate\Support\Facades\DB;
use Hashids,Session,Redirect,table;
use App\WEBRolOpcion,App\WEBListaCliente,App\STDTipoDocumento,App\WEBPrecioProducto,App\WEBReglaProductoCliente;
use App\WEBRegla,App\WEBUserEmpresaCentro,App\WEBPrecioProductoContrato,App\CMPCategoria,App\WEBPedido;
use App\WEBPrecioProductoContratoHistorial,App\WEBPrecioProductoHistorial,App\CMPOrden,App\CMPDetalleProducto,App\WEBDetallePedido;
use App\STDEmpresa,App\ALMCentro,App\STDEmpresaDireccion,App\CMPDocumentoCtble,App\WEBDocDoc;
use App\WEBDetalleDocumentoAsociados,App\WEBReglaCreditoCliente,App\WEBDetalleOrdenDespacho;
use App\User;
use Keygen;
use PDO;

class Funcion{


	public function totales_kilos_palets_tabla($ordendespacho_id,$grupo_movil,$atributo){
	    
	    $total = 0;
		$listadetalleordendespacho    =	WEBDetalleOrdenDespacho::where('ordendespacho_id','=',$ordendespacho_id)->get();
		foreach($listadetalleordendespacho as $index => $item){
			if($item->grupo_movil == $grupo_movil){
	    		 $total = $total + (float)$item->$atributo;
	    	}

		}
 	    return $total;
	}

	public function totales_kilos_palets($toOrderArray,$grupo_movil,$atributo){
	    
	    $total = 0;
	    foreach($toOrderArray as $key => $row) {

	    	if($row['grupo_movil'] == $grupo_movil){
	    		 $total = $total + (float)$toOrderArray[$key][$atributo];
	    	}
	    } 
 	    return $total;

	}





	public function llenar_array_productos($empresa_cliente_id,$empresa_cliente_nombre,$orden_id,$orden_cen,$fecha_pedido,
										   $fecha_entrega,$producto_id,$nombre_producto,$unidad_medida_id,$nombre_unidad_medida,
										   $cantidad,$kilos,$cantidad_sacos,$palets,$grupo,
										   $grupo_orden,$grupo_movil,$grupo_orden_movil,$correlativo,$tipo_grupo_oc,
										   $presentacion_producto){



		return						array(
											"empresa_cliente_id" 		=> $empresa_cliente_id,
											"empresa_cliente_nombre" 	=> $empresa_cliente_nombre,
											"orden_id" 					=> $orden_id,
											"orden_cen" 				=> $orden_cen,
											"fecha_pedido" 				=> $fecha_entrega,
											"fecha_entrega" 			=> $fecha_entrega,
								            "producto_id" 				=> $producto_id,
								            "nombre_producto" 			=> $nombre_producto,
								            "unidad_medida_id" 			=> $unidad_medida_id,
								            "nombre_unidad_medida" 		=> $nombre_unidad_medida,
								            "cantidad" 					=> $cantidad,
								            "kilos" 					=> $kilos,
								            "cantidad_sacos" 			=> $cantidad_sacos,
								            "palets" 					=> $palets,
								            "grupo" 					=> $grupo,
								            "grupo_orden" 				=> $grupo_orden,
								            "grupo_movil" 				=> $grupo_movil,
								            "grupo_orden_movil" 		=> $grupo_orden_movil,
								            "correlativo" 				=> $correlativo,
								            "tipo_grupo_oc" 			=> $tipo_grupo_oc,
								            "presentacion_producto"     => $presentacion_producto,
								            "muestra"     				=> '0'
								        );



	}





	public function lista_saldo_cuenta_documento($fecha_corte,$tipo_contrato,$cliente_id,$clase_con){

		$empresa_id = Session::get('empresas')->COD_EMPR;

        $stmt 	= 	DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC WEB.CMP_SALDO_CUENTA_DOCUMENTO 
        			@COD_EMPR = ?,
        			@FEC_CORTE = ?,
        			@TIPO = ?,
        			@CLIENTE = ?,
        			@CLASECON = ?'
        			);
        $stmt->bindParam(1, $empresa_id ,PDO::PARAM_STR);                   
        $stmt->bindParam(2, $fecha_corte  ,PDO::PARAM_STR);
        $stmt->bindParam(3, $tipo_contrato ,PDO::PARAM_STR);                   
        $stmt->bindParam(4, $cliente_id  ,PDO::PARAM_STR);                 
        $stmt->bindParam(5, $clase_con  ,PDO::PARAM_STR); 
        $stmt->execute();

        return $stmt;

	}

	public function crearrolwpan($field_grupo, $index, $grupo){

		$sw_crear  =  0;
        //es el primer valor
        if($index == 0){
          	$grupo     =  $field_grupo;
          	$sw_crear  =  1;  
        }else{
        	//es el segundo hasta el fianl valor
            if($field_grupo == $grupo){
                $sw_crear  =  0;
            }else{
                $sw_crear  =  1;
                $grupo     =  $field_grupo;
            }
        }
        
		$array_respuesta 		=	array(
											"sw_crear" 		=> $sw_crear,
											"grupo" 		=> $grupo,
								        );

        return $array_respuesta;

	}

	public function countgrupomovil($toOrderArray, $field, $grupo){
		$count 	=	0;
	    foreach($toOrderArray as $key => $row) {
	    	if($grupo == $row[$field]){
	    		$count 	= 	$count + 1;
	    	}
	    } 
 	    return $count; 
	}


	public function modificarmultidimensionalarray($toOrderArray, $field, $valor ,$orden_cen){

	    foreach($toOrderArray as $key => $row) {
	    	if($orden_cen == $row['orden_cen']){
	    		$toOrderArray[$key][$field] = $valor;
	    	}
	    } 
 	    return $toOrderArray; 
	}

	public function modificar_individual_multidimensionalarray($toOrderArray, $field){

	    foreach($toOrderArray as $key => $row) {
	    	$toOrderArray[$key][$field] = "1";
	    } 
 	    return $toOrderArray; 
	}


	public function ordermultidimensionalarray($toOrderArray, $field, $inverse){  
	    $position = array();  
	    $newRow = array();  
	    foreach ($toOrderArray as $key => $row) {  
	            $position[$key]  = $row[$field];  
	            $newRow[$key] = $row;  
	    }  
	    if ($inverse) {  
	        arsort($position);  
	    }  
	    else {  
	        asort($position);  
	    }  
	    $returnArray = array();  
	    foreach ($position as $key => $pos) {       
	        $returnArray[] = $newRow[$key];  
	    }  
	    return $returnArray;  
	}



	public function lista_orden_cen_detalle($orden_cen_id){

		$tipo_operacion = 'SEL';

        $stmt 	= 	DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.DETALLE_PRODUCTO_LISTAR 
        			@IND_TIPO_OPERACION = ?,
        			@COD_TABLA = ?');
        $stmt->bindParam(1, $tipo_operacion ,PDO::PARAM_STR);                   
        $stmt->bindParam(2, $orden_cen_id  ,PDO::PARAM_STR);                        			
        $stmt->execute();

        return $stmt;

	}


	public function lista_orden_cen($empresa_id,$cliente_id,$centro_id,$fecha_inicio,$fecha_fin){

		$tipo_operacion = 'LIS';
		$tipo_orden_id 	= 'TOR0000000000024';


        $stmt 	= 	DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.ORDEN_LISTAR 
        			@IND_TIPO_OPERACION = ?, 
        			@COD_EMPR = ?, 
        			@COD_CATEGORIA_TIPO_ORDEN = ?, 
        			@COD_EMPR_CLIENTE = ?,
        			@COD_CENTRO = ?, 
        			@FEC_ORDEN = ?, 
        			@FEC_ORDEN_FIN = ?');
        
        $stmt->bindParam(1, $tipo_operacion ,PDO::PARAM_STR);                   
        $stmt->bindParam(2, $empresa_id  ,PDO::PARAM_STR);                        			
        $stmt->bindParam(3, $tipo_orden_id ,PDO::PARAM_STR);                           			
        $stmt->bindParam(4, $cliente_id  ,PDO::PARAM_STR);                        		
        $stmt->bindParam(5, $centro_id ,PDO::PARAM_STR);                           			
        $stmt->bindParam(6, $fecha_inicio  ,PDO::PARAM_STR);
        $stmt->bindParam(7, $fecha_fin  ,PDO::PARAM_STR);
        $stmt->execute();

        return $stmt;

	}



	public function data_detalle_producto_sum_cantidad($documento_id,$producto_id) {

		//devuelve las nota de credito asociada a la boletas
		$nota_credito 					=	$this->boleta_o_factura_asociada_nota_credito($documento_id,'TDO0000000000007');
		$array_documentos_id 			= 	$this->colocar_en_array_id_documentos_asociados_foreach($nota_credito);

		$producto    					=   CMPDetalleProducto::where('CMP.DETALLE_PRODUCTO.COD_ESTADO','=',1)
				                            ->whereIn('CMP.DETALLE_PRODUCTO.COD_TABLA',$array_documentos_id)
				                            ->where('CMP.DETALLE_PRODUCTO.COD_PRODUCTO','=',$producto_id)
				                            ->select(DB::raw('sum(CAN_PRODUCTO) as CAN_PRODUCTO, COD_PRODUCTO'))
				                            ->groupBy('CMP.DETALLE_PRODUCTO.COD_PRODUCTO')
				                            ->first();

		return $producto;

	}


	public function ind_faltante_en_boletas_nota_credito($documento_id) {

		//devuelve las nota de credito asociada a la boletas
		$ind_faltante 					= 	'terminada';
		$nota_credito 					=	$this->boleta_o_factura_asociada_nota_credito($documento_id,'TDO0000000000007');
		$array_documentos_id 			= 	$this->colocar_en_array_id_documentos_asociados_foreach($nota_credito);

		//detalle producto de boletas
		$detalle_producto_boleta    	=   CMPDetalleProducto::where('CMP.DETALLE_PRODUCTO.COD_ESTADO','=',1)
			                                ->where('CMP.DETALLE_PRODUCTO.COD_TABLA','=',$documento_id)
			                                ->get();

		foreach($detalle_producto_boleta as $index => $item){

			//detalle producto suma de cantidades
			$producto    				=   CMPDetalleProducto::where('CMP.DETALLE_PRODUCTO.COD_ESTADO','=',1)
				                            ->whereIn('CMP.DETALLE_PRODUCTO.COD_TABLA',$array_documentos_id)
				                            ->where('CMP.DETALLE_PRODUCTO.COD_PRODUCTO','=',$item->COD_PRODUCTO)
				                            ->select(DB::raw('sum(CAN_PRODUCTO) as CAN_PRODUCTO, COD_PRODUCTO'))
				                            ->groupBy('CMP.DETALLE_PRODUCTO.COD_PRODUCTO')
				                            ->first();
			if(count($producto)>0){
				if($item->CAN_PRODUCTO > $producto->CAN_PRODUCTO){
					$ind_faltante 		= 	'parcialmente';
				}
			}else{
				$ind_faltante 			= 	'parcialmente';
			}                        

		}

		return $ind_faltante;

	}



	public function array_boleta_o_factura_asociada_nota_credito($documento_id,$producto_id) {

		$detalle_documento_asociado  = 	WEBDetalleDocumentoAsociados::where('documento_id','=',$documento_id)
										->where('producto_id','=',$producto_id)->first();

		return 	$detalle_documento_asociado;				

	}


	public function data_detalle_documento_asociado($documento_id,$producto_id) {

		$detalle_documento_asociado  = 	WEBDetalleDocumentoAsociados::where('documento_id','=',$documento_id)
										->where('producto_id','=',$producto_id)->first();

		return 	$detalle_documento_asociado;				

	}

	public function nota_credit_referencia_div($nota_credito_id,$tipodocumento) {

		$tipo_operacion = 'GEN';
		$cod_tabla 		= $nota_credito_id;
		$vacio 			= '';
		$estado 		= 1;

        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.REFERENCIA_ASOC_LISTAR ?,?,?,?,?,?,?,?,?,?,?');
        $stmt->bindParam(1, $tipo_operacion ,PDO::PARAM_STR);                           //@IND_TIPO_OPERACION='GEN',
        $stmt->bindParam(2, $vacio  ,PDO::PARAM_STR);                        			//@COD_TABLA='',
        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                           			//@COD_TIPO_TABLA='',
        $stmt->bindParam(4, $cod_tabla  ,PDO::PARAM_STR);                        		//@COD_TABLA_ASOC='ISLMVR0000006713',
        $stmt->bindParam(5, $vacio ,PDO::PARAM_STR);                           			//@COD_TIPO_TABLA_ASOC='',
        $stmt->bindParam(6, $vacio  ,PDO::PARAM_STR);                        			//@TXT_TABLA='',
        $stmt->bindParam(7, $vacio ,PDO::PARAM_STR);                           			//@TXT_TABLA_ASOC='',
        $stmt->bindParam(8, $vacio  ,PDO::PARAM_STR);                        			//@TXT_GLOSA='',
        $stmt->bindParam(9, $vacio ,PDO::PARAM_STR);                           			//@TXT_TIPO_REFERENCIA='',
        $stmt->bindParam(10, $vacio  ,PDO::PARAM_STR);                       			//@TXT_REFERENCIA='',
        $stmt->bindParam(11, $estado ,PDO::PARAM_STR);                          		//@COD_ESTADO=1,
        $stmt->execute();

		$i 								= 0;
		$array_documentos_id 			= array();
		while($row = $stmt->fetch())
		{
			$array_documentos_id[$i] 	=   $row['COD_TABLA'];
			$i= $i +1;
		}

		$documento_div 		= 	CMPDocumentoCtble::whereIn('COD_DOCUMENTO_CTBLE',$array_documentos_id)
								->where('COD_CATEGORIA_TIPO_DOC','=',$tipodocumento)
								->first();
		return 	$documento_div;	

	}


	public function colocar_en_array_id_documentos_asociados_foreach($lista_documento_asociados){
		$array_documentos_id 			= array();
		foreach($lista_documento_asociados as $index => $item){
			$array_documentos_id[$index] 	=   $item->COD_DOCUMENTO_CTBLE;
		}
		return 	$array_documentos_id;
	}


	public function colocar_en_array_id_documentos_asociados($lista_documento_asociados) {
		$i 								= 0;
		$array_documentos_id 			= array();
		while($row = $lista_documento_asociados->fetch())
		{
			$array_documentos_id[$i] 	=   $row['COD_TABLA'];
			$i= $i +1;
		}
		return 	$array_documentos_id;
	}



	public function lista_documentos_contables_array($array_documentos_id,$tipodocumento) {

		$lista_documento_contable 	= 	CMPDocumentoCtble::whereIn('COD_DOCUMENTO_CTBLE',$array_documentos_id)
										->where('COD_CATEGORIA_TIPO_DOC','=',$tipodocumento)
										->where('COD_EMPR_RECEPTOR','=','IACHEM0000006957')
										->orderBy('CAN_TOTAL', 'desc')
										->get();

		return 	$lista_documento_contable;							

	}


	public function data_documento_ctbl($documento_id) {

		$documento 				= 	CMPDocumentoCtble::where('COD_DOCUMENTO_CTBLE','=',$documento_id)->first();
        return $documento;

	}


	public function data_documento($documento_id) {

		$tipo_operacion = 'SEL';

        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.DOCUMENTO_CTBLE_LISTAR ?,?');
        $stmt->bindParam(1, $tipo_operacion ,PDO::PARAM_STR);                       //@IND_TIPO_OPERACION='SEL',
        $stmt->bindParam(2, $documento_id  ,PDO::PARAM_STR);                        //@COD_DOCUMENTO_CTBLE='ISLMGRR000003384',
        $stmt->execute();
        $documento = $stmt->fetch(2);
        return $documento;

	}

	public function lista_referencia_orden_venta($orden_venta_id) {

		$tipo_operacion = 'GEN';
		$cod_tabla 		= $orden_venta_id;
		$vacio 			= '';
		$estado 		= 1;

        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.REFERENCIA_ASOC_LISTAR ?,?,?,?,?,?,?,?,?,?,?');
        $stmt->bindParam(1, $tipo_operacion ,PDO::PARAM_STR);                           //@IND_TIPO_OPERACION='GEN',
        $stmt->bindParam(2, $vacio  ,PDO::PARAM_STR);                        			//@COD_TABLA='',
        $stmt->bindParam(3, $vacio ,PDO::PARAM_STR);                           			//@COD_TIPO_TABLA='',
        $stmt->bindParam(4, $cod_tabla  ,PDO::PARAM_STR);                        		//@COD_TABLA_ASOC='ISLMVR0000006713',
        $stmt->bindParam(5, $vacio ,PDO::PARAM_STR);                           			//@COD_TIPO_TABLA_ASOC='',
        $stmt->bindParam(6, $vacio  ,PDO::PARAM_STR);                        			//@TXT_TABLA='',
        $stmt->bindParam(7, $vacio ,PDO::PARAM_STR);                           			//@TXT_TABLA_ASOC='',
        $stmt->bindParam(8, $vacio  ,PDO::PARAM_STR);                        			//@TXT_GLOSA='',
        $stmt->bindParam(9, $vacio ,PDO::PARAM_STR);                           			//@TXT_TIPO_REFERENCIA='',
        $stmt->bindParam(10, $vacio  ,PDO::PARAM_STR);                       			//@TXT_REFERENCIA='',
        $stmt->bindParam(11, $estado ,PDO::PARAM_STR);                          		//@COD_ESTADO=1,
        $stmt->execute();
        return $stmt;

	}


	public function lista_detalle_producto_orden_venta($orden_venta_id,$producto_id) {

		$tipo_operacion = 'SEL';

        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.DETALLE_PRODUCTO_LISTAR ?,?,?');
        $stmt->bindParam(1, $tipo_operacion ,PDO::PARAM_STR);                           //@IND_TIPO_OPERACION='SEL',
        $stmt->bindParam(2, $orden_venta_id  ,PDO::PARAM_STR);                        	//@COD_TABLA='ISLMGRR000003384',
        $stmt->bindParam(3, $producto_id  ,PDO::PARAM_STR);                        		//@COD_PRODUCTO='',
        $stmt->execute();
        return $stmt;

	}




	public function lista_orden_venta_array_orden($array_orden) {

		$lista_orden 		= 		CMPOrden::whereIn('CMP.ORDEN.COD_ORDEN',$array_orden)->get();
		return $lista_orden;				

	}




	public function boleta_o_factura_asociada_nota_credito($documento_id,$tipo_documento_id) {

		//devuelve nota de credito
		$nota_credito 			=	WEBDocDoc::where('COD_CATEGORIA_TIPO_DOC','=',$tipo_documento_id)
									->where('COD_DOCUMENTO_CTBLE_BF','=',$documento_id)
									->get();

		return $nota_credito;
	}



	public function array_orden_venta_documento_fechas_cuenta($tipo_documento_id,$fecha_inicio,$fecha_fin,$cuenta_id) {


		$parametro_1 		= 		'CMP.ORDEN';
		$parametro_2 		= 		'CMP.DOCUMENTO_CTBLE';

		$array_orden 		= 		CMPOrden::join('CMP.REFERENCIA_ASOC', function ($join) use ($parametro_1,$parametro_2){
							            $join->on('CMP.REFERENCIA_ASOC.COD_TABLA', '=', 'CMP.ORDEN.COD_ORDEN')
							            //->where('CMP.REFERENCIA_ASOC.TXT_TABLA','=',$parametro_1)
							            ->whereIn('CMP.REFERENCIA_ASOC.TXT_TABLA',[$parametro_1])
							            ->whereIn('CMP.REFERENCIA_ASOC.TXT_TABLA_ASOC',[$parametro_2])
							            //->where('CMP.REFERENCIA_ASOC.TXT_TABLA_ASOC','=',$parametro_2)
							            ->where('CMP.REFERENCIA_ASOC.COD_ESTADO ','=',1);
							        })
									->join('CMP.DOCUMENTO_CTBLE', function ($join) use ($tipo_documento_id){
							            $join->on('CMP.DOCUMENTO_CTBLE.COD_DOCUMENTO_CTBLE', '=', 'CMP.REFERENCIA_ASOC.COD_TABLA_ASOC')
							            ->where('CMP.DOCUMENTO_CTBLE.COD_ESTADO','=',1)
							            ->where('CMP.DOCUMENTO_CTBLE.COD_CATEGORIA_TIPO_DOC','=',$tipo_documento_id);
							        })
									->join('CMP.CATEGORIA', function ($join) {
							            $join->on('CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN')
							            ->where('CMP.CATEGORIA.TXT_GLOSA','=','VENTAS')
							            ->where('CMP.CATEGORIA.COD_ESTADO','=',1);
							        })
									->where('CMP.ORDEN.COD_ESTADO','=',1)        
									->where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
									//->where('CMP.ORDEN.FEC_ORDEN','>=',$fecha_inicio) 
									//->where('CMP.ORDEN.FEC_ORDEN','<=',$fecha_fin)
		                            ->where('CMP.ORDEN.COD_CONTRATO','=',$cuenta_id)
		                            ->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN','=','EOR0000000000003') // solo ordenes terminadas
							        /*->where(function ($query){
					                    $query->where('CMP.ORDEN.COD_CATEGORIA_ESTADO_ORDEN', '=', 'EOR0000000000005')
					                    ->orWhere('CMP.ORDEN.COD_ESTADO', '=', 1);
									})*/
									->groupBy('CMP.ORDEN.COD_ORDEN')
									->pluck('CMP.ORDEN.COD_ORDEN')
									->toArray();


		return $array_orden;
													

	}


	public function data_direccion_empresa($empresa_id) {

		$direccion 		= 		STDEmpresaDireccion::where('COD_EMPR','=',$empresa_id)->first();
		return $direccion;				

	}

	public function data_direccion($direccion_id) {

		$direccion 		= 		STDEmpresaDireccion::where('COD_DIRECCION','=',$direccion_id)->first();
		return $direccion;				

	}

	public function data_cliente($contrato_id) {

		$direccion 		= 		WEBListaCliente::where('COD_CONTRATO','=',$contrato_id)->first();
		return $direccion;				

	}

	public function data_cliente_cliente_id($cliente_id) {

		$cliente 		= 		WEBListaCliente::where('id','=',$cliente_id)->first();
		return $cliente;				

	}


	public function cambiar_estado_detalle_pedido($detalle_pedido_id,$mensaje,$estado_pedido){

		$fechaactual 				= 	date('d-m-Y H:i:s');
		$mensaje					=   $mensaje;
		$error						=   false;

		$detalle_pedido 			= 	WEBDetallePedido::where('id','=',$detalle_pedido_id)->first();

		if($detalle_pedido->estado_id == $estado_pedido){
			
			$mensaje = 'El producto ya esta con estado rechazado';
			$error   = true;

		}else{

		    $detalle_pedido->estado_id 			= 	$estado_pedido;
			$detalle_pedido->fecha_mod 	 		=   $fechaactual;
			$detalle_pedido->usuario_mod 		=   Session::get('usuario')->id;
			$detalle_pedido->save();

		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;


	}

	public function data_regla_limite_credito($cliente_id) {

        $limite_credito     =   WEBReglaCreditoCliente::where('cliente_id','=',$cliente_id)->first();
        return 	$limite_credito;		

	}


	public function data_regla_producto_cliente($regla_producto_cliente_id) {

		$regla_producto_cliente 		= 		WEBReglaProductoCliente::where('id','=',$regla_producto_cliente_id)->first();
		return $regla_producto_cliente;				

	}

	public function asignar_precio_estandar_producto_empresa($empresa_id,$producto_id,$precio) {

		$fechaactual 				= 	date('d-m-Y H:i:s');
		$precioproducto             =   WEBPrecioProducto::where('producto_id','=',$producto_id)
										->where('empresa_id','=',$empresa_id)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();

		if(count($precioproducto)<=0){

			/****** AGREGAR PRECIO PRODUCTO **********/
			$idprecioproducto 			=  	$this->getCreateIdMaestra('WEB.precioproductos');
			$cabecera            	 	=	new WEBPrecioProducto;
			$cabecera->id 	     	 	=   $idprecioproducto;
			$cabecera->precio 	     	=   $precio;
			$cabecera->fecha_crea 	 	=   $fechaactual;
			$cabecera->usuario_crea 	=   Session::get('usuario')->id;
			$cabecera->producto_id 	 	= 	$producto_id;
			$cabecera->empresa_id 		=   $empresa_id;
			$cabecera->centro_id 		=   Session::get('centros')->COD_CENTRO;
			$cabecera->save();

		}


	}




	public function el_pedido_estado_generado($pedido_id,$mensaje) {


		$mensaje					=   $mensaje;
		$error						=   false;

		$pedido 					=   WEBPedido::where('id','=',$pedido_id)->where('estado_id','=','EPP0000000000002')->first();

		if(count($pedido) <= 0){
			$mensaje = 'Esta pedido no esta en estado "GENERADO" no se puede actualizar';
			$error   = true;
		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;


	}


	public function calculo_totales_pedido($pedido_id) {


		$fechaactual 				= 	date('d-m-Y H:i:s');
		$detallepedido 				= 	WEBDetallePedido::where('pedido_id','=',$pedido_id)
										->where('activo','=','1')
										->select(DB::raw('sum(total) as total'))
										->first();

		$pedido 					=   WEBPedido::where('id','=',$pedido_id)->first();
	    $pedido->igv 				= 	$this->calculo_igv($detallepedido->total);
	    $pedido->subtotal 			= 	$this->calculo_subtotal($detallepedido->total);
	    $pedido->total 				= 	$detallepedido->total;
		$pedido->fecha_mod 	 		=   $fechaactual;
		$pedido->usuario_mod 		=   Session::get('usuario')->id;
		$pedido->save();
			

	}




	public function color_empresa($empresa_id) {

		$color 		= '';
		if($empresa_id == 'IACHEM0000010394'){
			$color 		= 'color-iin';
		}

		if($empresa_id == 'IACHEM0000007086'){
			$color 		= 'color-ico';
		}
		if($empresa_id == 'EMP0000000000007'){
			$color 		= 'color-itr';
		}

		if($empresa_id == 'IACHEM0000001339'){
			$color 		= 'color-ich';
		}

		if($empresa_id == 'EMP0000000000001'){
			$color 		= 'color-iaa';
		}
		return $color;
	}



	public function data_categoria_documento($documento_id) {

		$documento 		= 	CMPDocumentoCtble::where('COD_DOCUMENTO_CTBLE','=',$documento_id)
							->first();

		$categoria 		= 	CMPCategoria::where('COD_CATEGORIA','=',$documento->COD_CATEGORIA_ESTADO_DOC_CTBLE)->first();
		return $categoria;


	}


	public function data_categoria($categoria_id) {

		$categoria 		= 		CMPCategoria::where('COD_CATEGORIA','=',$categoria_id)->first();
		return $categoria;				

	}


	public function data_centro($centro_id) {

		$centro 		= 		ALMCentro::where('COD_CENTRO','=',$centro_id)->first();
		return $centro;				

	}


	public function data_empresa($empresa_id) {

		$empresa 		= 		STDEmpresa::where('COD_EMPR','=',$empresa_id)->first();
		return $empresa;				

	}

	public function data_usuario($usuario_id) {

		$usuario 		= 		User::where('id','=',$usuario_id)->first();
		return $usuario;				

	}

	public function pedido_producto_rechazado($pedido) {

		$detalle_pedido 		= 		WEBDetallePedido::where('pedido_id','=',$pedido->id)
										->where('estado_id','=','EPP0000000000005')
										->where('activo','=',1)
										->get();

		return count($detalle_pedido);				

	}


	public function pedido_producto_registrado($pedido) {

		$detalle_pedido 		= 		WEBDetallePedido::where('pedido_id','=',$pedido->id)
										->where('estado_id','=','EPP0000000000004')
										->where('activo','=',1)
										->get();

		return count($detalle_pedido);				

	}



	public function pedido_producto_total($pedido) {

		$detalle_pedido 		= 		WEBDetallePedido::where('pedido_id','=',$pedido->id)
										->where('activo','=',1)
										->get();

		return count($detalle_pedido);				

	}


	public function estado_pedido_ejecutado($pedido) {


		$fechaactual 			= 		date('d-m-Y H:i:s');
		$detalle_pedido 		= 		WEBDetallePedido::where('pedido_id','=',$pedido->id)
										->where('activo','=',1)
										->whereNotIn('estado_id',['EPP0000000000004','EPP0000000000005'])
										->get();

		if(count($detalle_pedido)<=0){

            $cabecera                               =   WEBPedido::find($pedido->id);
			$cabecera->fecha_mod 	 				=   $fechaactual;
			$cabecera->usuario_mod 					=   Session::get('usuario')->id;
            $cabecera->estado_id                    =   'EPP0000000000004';
            $cabecera->ind_notificacion_despacho    =   0;
            $cabecera->save();

		}								


	}


	public function json_detalle_pedido($pedido_id) {

		$json_detalle_pedido = 		WEBDetallePedido::where('pedido_id','=',$pedido_id)
									->where('activo','=',1)
									->select(DB::raw("	id as detalle_pedido_id,
													  	empresa_id,
													    (CASE   
														      WHEN estado_id != 'EPP0000000000004' and estado_id != 'EPP0000000000005' THEN 'checked'   
														      ELSE ''   
														END) as checked,
													  	estado_id"))
									->get()
									->toJson();

		return $json_detalle_pedido;

	}


	public function grouparray($array,$groupkey)
	{
	 if (count($array)>0)
	 {
	 	$keys = array_keys($array[0]);
	 	$removekey = array_search($groupkey, $keys);		if ($removekey===false)
	 		return array("Clave \"$groupkey\" no existe");
	 	else
	 		unset($keys[$removekey]);
	 	$groupcriteria = array();
	 	$return=array();
	 	foreach($array as $value)
	 	{
	 		$item=null;
	 		foreach ($keys as $key)
	 		{
	 			$item[$key] = $value[$key];
	 		}
	 	 	$busca = array_search($value[$groupkey], $groupcriteria);
	 		if ($busca === false)
	 		{
	 			$groupcriteria[]=$value[$groupkey];
	 			$return[]=array($groupkey=>$value[$groupkey],'groupeddata'=>array());
	 			$busca=count($return)-1;
	 		}
	 		$return[$busca]['groupeddata'][]=$item;
	 	}
	 	return $return;
	 }
	 else
	 	return array();
	}



	public function calculo_precio_venta($cliente,$producto,$fechadia) {


		$precio_regular 	=	0;
		$fechadia 			= 	date_format(date_create($fechadia), 'Y-m-d');


		$precio_producto 			= 	CMPOrden::join('CMP.DETALLE_PRODUCTO', 'CMP.ORDEN.COD_ORDEN', '=', 'CMP.DETALLE_PRODUCTO.COD_TABLA')
												->where('CMP.ORDEN.COD_CATEGORIA_TIPO_ORDEN','=','TOR0000000000024')
												->where('CMP.ORDEN.fec_orden','=',$fechadia)
												->where('CMP.ORDEN.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
												->where('CMP.ORDEN.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
												->where('CMP.ORDEN.COD_CONTRATO','=',$cliente->COD_CONTRATO)
												->where('CMP.DETALLE_PRODUCTO.COD_PRODUCTO','=',$producto->producto_id)
												->first();

		if(count($precio_producto)){
			$precio_regular 	=	$precio_producto->CAN_PRECIO_UNIT;
		}

		return $precio_regular;
	 			
	}




	//cambio 

	public function combo_jefe_ventas() {


        $lista_jefes_ventas = 		CMPCategoria::where('CMP.CATEGORIA.COD_ESTADO','=',1)
        							->where('CMP.CATEGORIA.IND_ACTIVO','=',1)
        							->where('CMP.CATEGORIA.TXT_GRUPO', '=' , 'JEFE_VENTA')
							        ->where('CMP.CATEGORIA.TXT_ABREVIATURA','=', Session::get('centros')->COD_CENTRO)
									->pluck('CMP.CATEGORIA.NOM_CATEGORIA','CMP.CATEGORIA.COD_CATEGORIA')
									->toArray();

		$combo_jefes_ventas  	= 	array('' => "Seleccione Responsable",'1' => "TODOS") + $lista_jefes_ventas;
		return $combo_jefes_ventas;		 			
	}



	public function lista_precios_departamento_cliente($contrato_id,$producto_id,$cliente_id) {


		$departamento_id 					= 	"";
		$lista_reglas_departamento 			= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->join('CMP.CATEGORIA', 'WEB.reglas.departamento_id', '=', 'CMP.CATEGORIA.COD_CATEGORIA')
												->where('WEB.reglaproductoclientes.activo','=','1')
												->where('WEB.reglas.activo','=','1')
												->where('WEB.reglas.estado','=','PU')
												->where('WEB.reglas.tiporegla','=','PRD')
												->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
												->where('WEB.reglaproductoclientes.cliente_id','=',$cliente_id)
												->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
												->get();


		$lista_precio_departamento = array();
		$cadena = '';
		// RECORRER TODOS LOS DEPARTAMENTOS CON SU PRECIO
		foreach($lista_reglas_departamento as $item){

			$departamento_id 	= 	trim($item->departamento_id);

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

	        $cadena	=	$item->NOM_CATEGORIA.' : S/. '.$resultado['precio'];
			array_push($lista_precio_departamento, $cadena);

		}

	 	return   $lista_precio_departamento;				 			
	}



	public function reglas_producto_fecha_sub_canales($producto_id,$fechadia) {



		$reglas 						=	'';
		$fechadia 						= 	date_format(date_create($fechadia), 'Y-m-d');
									
	    $array_cliente_contrato 		= 	WEBListaCliente::whereIn('COD_CATEGORIA_SUB_CANAL',['SCV0000000000004' ,'SCV0000000000020','SCV0000000000005'])
					    					->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    					->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
											->pluck('COD_CONTRATO')
											->toArray();


		//historial de todas las reglas que tenia
		$lista_reglas_cliente 			= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->where('WEB.reglas.tiporegla','=','POV')
												//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
												->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
												->whereIn('WEB.reglaproductoclientes.contrato_id',$array_cliente_contrato)
												//->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
												->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
												->whereNotNull('WEB.reglaproductoclientes.fecha_mod')
												->whereRaw('Convert(varchar(10), WEB.reglaproductoclientes.fecha_crea, 120) <= ?', [$fechadia])
												->whereRaw('Convert(varchar(10), WEB.reglaproductoclientes.fecha_mod, 120) >= ?', [$fechadia])
												->select('WEB.reglas.codigo','WEB.reglas.descuento')
												->groupBy('WEB.reglas.codigo')
												->groupBy('WEB.reglas.descuento')
												->get();									


		foreach($lista_reglas_cliente as $item){
			$reglas = $reglas . $item->codigo.' (S/.'.$item->descuento.' menos) ';
		}

		//ultima regla asignada
		$lista_reglas_cliente_ultima			= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
													->where('WEB.reglas.tiporegla','=','POV')
													//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
													->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
													->whereIn('WEB.reglaproductoclientes.contrato_id',$array_cliente_contrato)
													//->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
													->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
													->select('WEB.reglaproductoclientes.*','WEB.reglas.descuento','WEB.reglas.codigo')
													->whereNull('WEB.reglaproductoclientes.fecha_mod')
													->whereRaw('Convert(varchar(10), WEB.reglaproductoclientes.fecha_crea, 120) <= ?', [$fechadia])
													->first();


		if(count($lista_reglas_cliente_ultima)>0){
			$reglas = $reglas . $lista_reglas_cliente_ultima->codigo.' (S/.'.$lista_reglas_cliente_ultima->descuento.' menos) ';
		}									


		return $reglas;
			 			
	}



	public function descuento_reglas_producto_fecha($contrato_id,$producto_id,$cliente_id,$departamento_id,$fechadia) {



		$descuento 						=	0.0000;
		$fechadia 						= 	date_format(date_create($fechadia), 'Y-m-d');
									

		//historial de todas las reglas que tenia
		$lista_reglas_cliente 			= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->where('WEB.reglas.tiporegla','=','POV')
												//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
												->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
												->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
												->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
												->whereNotNull('WEB.reglaproductoclientes.fecha_mod')
												->select('WEB.reglaproductoclientes.*','WEB.reglas.descuento')
												->whereRaw('Convert(varchar(10), WEB.reglaproductoclientes.fecha_crea, 120) <= ?', [$fechadia])
												->whereRaw('Convert(varchar(10), WEB.reglaproductoclientes.fecha_mod, 120) >= ?', [$fechadia])
												->get();

		foreach($lista_reglas_cliente as $item){
			$descuento = $descuento + $item->descuento;
		}

		//ultima regla asignada
		$lista_reglas_cliente_ultima			= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->where('WEB.reglas.tiporegla','=','POV')
												//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
												->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
												->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
												->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
												->select('WEB.reglaproductoclientes.*','WEB.reglas.descuento')
												->whereNull('WEB.reglaproductoclientes.fecha_mod')
												->whereRaw('Convert(varchar(10), WEB.reglaproductoclientes.fecha_crea, 120) <= ?', [$fechadia])
												->first();


		if(count($lista_reglas_cliente_ultima)>0){
				$descuento = $descuento + $lista_reglas_cliente_ultima->descuento;
		}									


		return $descuento;
			 			
	}




	public function descuento_reglas_producto($contrato_id,$producto_id,$cliente_id,$departamento_id) {

		$departamento_id = trim($departamento_id);

		$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC web.descuento_regla_producto_contrato ?,?,?,?');
        $stmt->bindParam(1, $contrato_id ,PDO::PARAM_STR);
        $stmt->bindParam(2, $producto_id ,PDO::PARAM_STR);
        $stmt->bindParam(3, $cliente_id ,PDO::PARAM_STR);
        $stmt->bindParam(4, $departamento_id ,PDO::PARAM_STR); 
        $stmt->execute();
        $resultado = $stmt->fetch();
		return  $resultado['descuento'];
			 			
	}


	public function precio_descuento_reglas_producto($contrato_id,$producto_id,$cliente_id,$departamento_id) {

		$departamento_id = trim($departamento_id);

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
		return  $resultado['precio'];
			 			
	}



	public function lista_reglas_cliente($contrato_id,$producto_id) {


		$lista_reglas_cliente 			= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->where('WEB.reglaproductoclientes.activo','=','1')
												->where('WEB.reglas.activo','=','1')
												->where('WEB.reglas.estado','=','PU')
												->where('WEB.reglas.tiporegla','<>','PRD')
												//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
												->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
												->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
												->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
												->orderBy('WEB.reglas.departamento_id', 'asc')
												->get();

	 	return   $lista_reglas_cliente;				 			
	}




	public function lista_precio_regular_departamento($contrato_id,$producto_id) {


		$lista_precio_regular_departamento 	= 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
												->where('WEB.reglaproductoclientes.activo','=','1')
												->where('WEB.reglas.activo','=','1')
												->where('WEB.reglas.estado','=','PU')
												->where('WEB.reglas.tiporegla','=','PRD')
												//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
												->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
												->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
												->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
												->orderBy('WEB.reglas.departamento_id', 'asc')
												->get();

	 	return   $lista_precio_regular_departamento;				 			
	}



	public function lista_productos_reglas($cuenta_id) {

		$array_productos_id 	= 	WEBReglaProductoCliente::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
									->join('WEB.reglas', 'WEB.reglas.id', '=', 'WEB.reglaproductoclientes.regla_id')
									->where('WEB.reglas.activo','=','1')
									->where('WEB.reglas.estado','=','PU')
									->where('WEB.reglas.tiporegla','<>','PRD')
									//->where('WEB.reglas.empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('WEB.reglas.centro_id','=',Session::get('centros')->COD_CENTRO)
									->where('WEB.reglaproductoclientes.contrato_id','=',$cuenta_id)
									->where('WEB.reglaproductoclientes.activo','=','1')
									->pluck('WEB.reglaproductoclientes.producto_id')->toArray();


		$lista_producto_regla 	= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
					    			->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					    			->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->whereIn('producto_id',$array_productos_id)
	    					 		->orderBy('NOM_PRODUCTO', 'asc')->get();



	 	return   $lista_producto_regla;				 			
	}



	public function lista_productos_precio_favotitos($cuenta_id) {

		$lista_producto_precio 	= 	WEBPrecioProductoContrato::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
							    	->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					    			->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->where('contrato_id','=',$cuenta_id)
									->where('activo','=','1')
									->where('ind_contrato','=',1)
									->orderBy('NOM_PRODUCTO', 'asc')
									->get();

	 	return   $lista_producto_precio;				 			
	}


	public function combo_tipo_precio_productos_reglas() {

		$combotipoprecio_producto  	= 	array('2' => "Reglas" ,'1' => "Contratos" ,'0' => "Todos");
		return $combotipoprecio_producto;		 			
	}

	public function combo_tipo_precio_productos() {

		$combotipoprecio_producto  	= 	array('1' => "Contratos" ,'0' => "Todos");
		return $combotipoprecio_producto;		 			
	}


	public function combo_tipo_precio_productos_asignar() {
		$combotipoprecio_producto  	= 	array('0' => "Todos",'1' => "Contratos" ,);
		return $combotipoprecio_producto;		 			
	}


	public function tiene_contrato_activo($precioproducto_id,$contrato_id) {
		

		$precio_producto 		  	= 	WEBPrecioProducto::where('id','=',$precioproducto_id)->first();

		$precio_producto_contrato 	= 	WEBPrecioProductoContrato::where('producto_id','=',$precio_producto->producto_id)
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->where('contrato_id','=',$contrato_id)
										->first();

		if(count($precio_producto_contrato)>0){
			if($precio_producto_contrato->ind_contrato == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}

					 			
	}

	public function favorito_precio_producto_contrato($precioproducto_id,$contrato_id) {
		

		$precio_producto 		  	= 	WEBPrecioProducto::where('id','=',$precioproducto_id)->first();

		$precio_producto_contrato 	= 	WEBPrecioProductoContrato::where('producto_id','=',$precio_producto->producto_id)
										->where('empresa_id','=',$precio_producto->empresa_id)
										->where('centro_id','=',$precio_producto->centro_id)
										->where('contrato_id','=',$contrato_id)
										->first();

		if(count($precio_producto_contrato)>0){
			return true;
		}else{
			return false;
		}

					 			
	}



	public function calculo_precio_regular_fecha_subcanal($sub_canal_id,$producto,$fechadia) {


		$precio_regular 				=	'0.000';
		$fechadia 						= 	date_format(date_create($fechadia), 'Y-m-d');



		// lista de clientes del subcanal
	    $array_cliente_contrato 		= 	WEBListaCliente::SubCanal($sub_canal_id)
					    					->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    					->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
											->pluck('COD_CONTRATO')
											->toArray();
					    				
					    				
		//existe en esta tabla 
		$exiteprecio 					=	WEBPrecioProductoContrato::whereIn('contrato_id',$array_cliente_contrato)
											->where('producto_id','=',$producto->producto_id)
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->first();



		if(count($exiteprecio)>0){


			//existe ingreso de precio en la aplicacion 
			$primerregistro 	=	WEBPrecioProductoContrato::whereIn('contrato_id',$array_cliente_contrato)
									->where('producto_id','=',$producto->producto_id)
									->whereRaw('Convert(varchar(10), fecha_crea, 120) <= ?', [$fechadia])
									->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->first();


			if(count($primerregistro)>0){


				$precio_regular 	=	$primerregistro->precio;
				//ultimo precio ingresado
				$ultimoregistro 	=	WEBPrecioProductoContrato::whereIn('contrato_id',$array_cliente_contrato)
										->where('producto_id','=',$producto->producto_id)
							            ->where(function ($query) use($fechadia) {
							                $query->whereRaw('Convert(varchar(10), fecha_mod, 120) <= ?', [$fechadia])
							                      ->orwhereNull('fecha_mod');
							            })
										//->whereRaw('Convert(varchar(10), fecha_mod, 120) <= ?', [$fechadia])
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();



				if(count($ultimoregistro)>0){
					$precio_regular 	=	$ultimoregistro->precio;
				}else{



					//fecha anterior
					$preciohistorico 	=	WEBPrecioProductoContratoHistorial::whereIn('contrato_id',$array_cliente_contrato)
											->where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) < ?', [$fechadia])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'desc')
											->first();

					//precio historico
					$preciohistoricoreal 	=	WEBPrecioProductoContratoHistorial::whereIn('contrato_id',$array_cliente_contrato)
											->where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) > ?', [$preciohistorico->fecha_crea])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'asc')
											->first();

					$precio_regular 	=	$preciohistoricoreal->precio;

				}
			}
		}else{


			//existe ingreso de precio en la aplicacion 
			$primerregistro 	=	WEBPrecioProducto::where('producto_id','=',$producto->producto_id)
									->whereRaw('Convert(varchar(10), fecha_crea, 120) <= ?', [$fechadia])
									->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->first();

			if(count($primerregistro)>0){



				$precio_regular 	=	$primerregistro->precio;
				//ultimo precio ingresado
				$ultimoregistro 	=	WEBPrecioProducto::where('producto_id','=',$producto->producto_id)
							            ->where(function ($query) use($fechadia) {
							                $query->whereRaw('Convert(varchar(10), fecha_mod, 120) <= ?', [$fechadia])
							                      ->orwhereNull('fecha_mod');
							            })
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();

				if(count($ultimoregistro)>0){
					$precio_regular 	=	$ultimoregistro->precio;
				}else{

					//fecha anterior
					$preciohistorico 	=	WEBPrecioProductoHistorial::where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) < ?', [$fechadia])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'desc')
											->first();


					//precio historico
					$preciohistoricoreal 	=	WEBPrecioProductoHistorial::where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) > ?', [$preciohistorico->fecha_crea])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'asc')
											->first();

					$precio_regular 	=	$preciohistoricoreal->precio;

				}

			}



		}




	    							
		return $precio_regular;
	 			
	}



	public function calculo_precio_regular_fecha($cliente,$producto,$fechadia) {


		$precio_regular 	=	0;
		$fechadia 			= 	date_format(date_create($fechadia), 'Y-m-d');


		//existe en esta tabla 
		$exiteprecio 	=	WEBPrecioProductoContrato::where('contrato_id','=',$cliente->COD_CONTRATO)
							->where('producto_id','=',$producto->producto_id)
							->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
							->where('centro_id','=',Session::get('centros')->COD_CENTRO)
							->first();



		if(count($exiteprecio)>0){


			//existe ingreso de precio en la aplicacion 
			$primerregistro 	=	WEBPrecioProductoContrato::where('contrato_id','=',$cliente->COD_CONTRATO)
									->where('producto_id','=',$producto->producto_id)
									->whereRaw('Convert(varchar(10), fecha_crea, 120) <= ?', [$fechadia])
									->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->first();


			if(count($primerregistro)>0){



				$precio_regular 	=	$primerregistro->precio;
				//ultimo precio ingresado
				$ultimoregistro 	=	WEBPrecioProductoContrato::where('contrato_id','=',$cliente->COD_CONTRATO)
										->where('producto_id','=',$producto->producto_id)
							            ->where(function ($query) use($fechadia) {
							                $query->whereRaw('Convert(varchar(10), fecha_mod, 120) <= ?', [$fechadia])
							                      ->orwhereNull('fecha_mod');
							            })
										//->whereRaw('Convert(varchar(10), fecha_mod, 120) <= ?', [$fechadia])
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();



				if(count($ultimoregistro)>0){
					$precio_regular 	=	$ultimoregistro->precio;
				}else{



					//fecha anterior
					$preciohistorico 	=	WEBPrecioProductoContratoHistorial::where('contrato_id','=',$cliente->COD_CONTRATO)
											->where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) < ?', [$fechadia])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'desc')
											->first();

					//return count($preciohistorico);
					//precio historico
					$preciohistoricoreal 	=	WEBPrecioProductoContratoHistorial::where('contrato_id','=',$cliente->COD_CONTRATO)
											->where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) > ?', [$preciohistorico->fecha_crea])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'asc')
											->first();

					$precio_regular 	=	$preciohistoricoreal->precio;

				}
			}
		}else{


			//existe ingreso de precio en la aplicacion 
			$primerregistro 	=	WEBPrecioProducto::where('producto_id','=',$producto->producto_id)
									->whereRaw('Convert(varchar(10), fecha_crea, 120) <= ?', [$fechadia])
									->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
									->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->first();

			if(count($primerregistro)>0){



				$precio_regular 	=	$primerregistro->precio;
				//ultimo precio ingresado
				$ultimoregistro 	=	WEBPrecioProducto::where('producto_id','=',$producto->producto_id)
							            ->where(function ($query) use($fechadia) {
							                $query->whereRaw('Convert(varchar(10), fecha_mod, 120) <= ?', [$fechadia])
							                      ->orwhereNull('fecha_mod');
							            })
										->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
										->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();

				if(count($ultimoregistro)>0){
					$precio_regular 	=	$ultimoregistro->precio;
				}else{

					//fecha anterior
					$preciohistorico 	=	WEBPrecioProductoHistorial::where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) < ?', [$fechadia])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'desc')
											->first();


					//precio historico
					$preciohistoricoreal 	=	WEBPrecioProductoHistorial::where('producto_id','=',$producto->producto_id)
											->whereRaw('Convert(varchar(10), fecha_crea, 120) > ?', [$preciohistorico->fecha_crea])
											->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
											->where('centro_id','=',Session::get('centros')->COD_CENTRO)
											->orderBy('fecha_crea', 'asc')
											->first();

					$precio_regular 	=	$preciohistoricoreal->precio;

				}

			}



		}




	    							
		return $precio_regular;
	 			
	}


	public function calculo_precio_regular($cliente,$producto) {


		$precioregular =      	WEBPrecioProductoContrato::where('contrato_id','=',$cliente->COD_CONTRATO)
								->where('producto_id','=',$producto->producto_id)
								->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
								->where('centro_id','=',Session::get('centros')->COD_CENTRO)
								->first();


		if(count($precioregular)){
			return $precioregular->precio;
		}

		return $producto->precio;
	 			
	}



	public function combo_clientes_cuenta_seleccionada($cuenta_id) {

		$listaclientes   		=	WEBListaCliente::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    			->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
					    			->where('COD_CONTRATO','=',$cuenta_id)
									->pluck('NOM_EMPR','COD_CONTRATO')
									->toArray();

		$combolistaclientes  	= 	$listaclientes;
		return $combolistaclientes;		 			
	}

	public function combo_clientes_cuenta() {

		$listaclientes   		=	WEBListaCliente::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    			->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									->pluck('NOM_EMPR','COD_CONTRATO')
									->toArray();

		$combolistaclientes  	= 	array('' => "Seleccione cliente") + $listaclientes;
		return $combolistaclientes;		 			
	}



	public function combo_clientes() {

		$listaclientes   		=	WEBListaCliente::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    			->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									->pluck('NOM_EMPR','id')
									->toArray();

		$combolistaclientes  	= 	array('' => "Seleccione cliente") + $listaclientes;
		return $combolistaclientes;		 			
	}

	public function departamento($departamento_id) {


		$departamento_id = trim($departamento_id);
		$departamento   		=	CMPCategoria::where('TXT_PREFIJO','=','DEP')
									->where('COD_CATEGORIA','=',$departamento_id)
									->first();

		return 	$departamento;		 			
	}


	public function combo_departamentos() {

		$listadepartamentos   		=	CMPCategoria::where('TXT_PREFIJO','=','DEP')
										->where('COD_ESTADO','=',1)
										->pluck('NOM_CATEGORIA','COD_CATEGORIA')
										->toArray();

		$combolistadepartamentos  	= 	array('' => "Seleccione departamento") + $listadepartamentos;
		return $combolistadepartamentos;					 			
	}
	public function combo_condicionpago() {

		$listacat 		=	CMPCategoria::where('TXT_GRUPO','=','TIPO_PAGO')
										->where('COD_ESTADO','=',1)
										->pluck('NOM_CATEGORIA','COD_CATEGORIA')
										->toArray();

		$combolistacondicionpago 	= 	array('' => "Seleccione condicion pago") + $listacat;
		return $combolistacondicionpago;					 			
	}


	public function combo_departamentos_modificar($documento_id) {

		$listadepartamentos   		=	CMPCategoria::where('TXT_PREFIJO','=','DEP')
										->where('COD_ESTADO','=',1)
										->pluck('NOM_CATEGORIA','COD_CATEGORIA')
										->toArray();

		$nombre_departamento 		=   $this->departamento($documento_id)->NOM_CATEGORIA;

		$combolistadepartamentos  	= 	array($documento_id => $nombre_departamento) + $listadepartamentos;
		return $combolistadepartamentos;					 			
	}


	//18-10-2019
	public function precio_producto_contrato_empresa($precioproducto_id,$contrato_id,$empresa_id) {
		

		$precio_producto 		  	= 	WEBPrecioProducto::where('id','=',$precioproducto_id)->first();

		$precio_producto_contrato 	= 	WEBPrecioProductoContrato::where('producto_id','=',$precio_producto->producto_id)
										->where('empresa_id','=',$empresa_id)
										->where('centro_id','=',$precio_producto->centro_id)
										->where('contrato_id','=',$contrato_id)
										->first();

		if(count($precio_producto_contrato)>0){
			return $precio_producto_contrato->precio;
		}else{
			return $precio_producto->precio;
		}

					 			
	}


	public function precio_producto_contrato($precioproducto_id,$contrato_id) {
		

		$precio_producto 		  	= 	WEBPrecioProducto::where('id','=',$precioproducto_id)->first();

		$precio_producto_contrato 	= 	WEBPrecioProductoContrato::where('producto_id','=',$precio_producto->producto_id)
										->where('empresa_id','=',$precio_producto->empresa_id)
										->where('centro_id','=',$precio_producto->centro_id)
										->where('contrato_id','=',$contrato_id)
										->first();

		if(count($precio_producto_contrato)>0){
			return $precio_producto_contrato->precio;
		}else{
			return $precio_producto->precio;
		}

					 			
	}


	public function cuenta_cliente($id_cliente) {
		
		$cuenta 		= 		DB::table('WEB.LISTACLIENTE')
        							->where('id','=',$id_cliente)
        							->first();

	 	return  $cuenta->CONTRATO;					 			
	}


	public function tipo_cambio() {
		
		$tipocambio 		= 		DB::table('WEB.TIPOCAMBIO')
        							->where('FEC_CAMBIO','<=',date('d/m/Y'))
        							->orderBy('FEC_CAMBIO', 'desc')
        							->first();

        return $tipocambio; 							
	}




	public function desencriptar_id($id,$count) {
		
		$idarray = explode('-', $id);
	  	//decodificar variable
	  	$decid 	= Hashids::decode($idarray[1]);
	  	//ver si viene con letras la cadena codificada
	  	if(count($decid)==0){ 
	  		return Redirect::back()->withInput()->with('errorurl', 'Indices de la url con errores'); 
	  	}
	  	//concatenar con ceros
	  	$idcompleta = str_pad($decid[0], $count, "0", STR_PAD_LEFT); 
	  	//concatenar prefijo
		$idcompleta = $idarray[0].$idcompleta;
		return $idcompleta;
	}


	public function calcular_cabecera_total($productos) {

		$total 						=   0.0000;
		$productos 					= 	json_decode($productos, true);

		foreach($productos as $obj){
			$total = $total + (float)$obj['precio_producto']*(float)$obj['cantidad_producto'];
		}
		return $total;
	}

	public function calculo_igv($monto) {
	  	return $monto - ($monto/1.18);
	}
	public function calculo_subtotal($monto) {
	  	return $monto/1.18;
	}

	public function generar_codigo($basedatos,$cantidad) {

	  		// maximo valor de la tabla referente
			$tabla = DB::table($basedatos)
            ->select(DB::raw('max(codigo) as codigo'))
            ->get();

            //conversion a string y suma uno para el siguiente id
            $idsuma = (int)$tabla[0]->codigo + 1;

		  	//concatenar con ceros
		  	$correlativocompleta = str_pad($idsuma, $cantidad, "0", STR_PAD_LEFT); 

	  		return $correlativocompleta;

	}

	public function generar_lote($basedatos,$cantidad) {

	  		// maximo valor de la tabla referente
			$tabla = DB::table($basedatos)
            ->select(DB::raw('max(lote) as lote'))
            ->get();

            //conversion a string y suma uno para el siguiente id
            $idsuma = (int)$tabla[0]->lote + 1;

		  	//concatenar con ceros
		  	$lotecompleta = str_pad($idsuma, $cantidad, "0", STR_PAD_LEFT); 

	  		return $lotecompleta;

	}


	public function tiene_perfil($empresa_id,$centro_id,$usuario_id) {

		$perfiles 		=   WEBUserEmpresaCentro::where('empresa_id','=',$empresa_id)
							->where('centro_id','=',$centro_id)
							->where('usuario_id','=',$usuario_id)
							->where('activo','=','1')
							->first();

		if(count($perfiles)>0){
			return true;
		}else{
			return false;
		}	

	}

	public function precio_regla_calculo_menor_cero($producto_id,$cliente_id,$mensaje,$tiporegla,$regla_id) {

		$mensaje					=   $mensaje;
		$error						=   false;
		$precio 					=   WEBPrecioProducto::where('producto_id','=',$producto_id)
								    	->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					    				->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->first();

		$regla 						=   WEBRegla::where('id','=',$regla_id)->first();

		$calculo 					= 	$this->calculo_precio_regla($regla->tipodescuento,$precio->precio,$regla->descuento,$regla->descuentoaumento);

		if($calculo < 0 && $regla->descuentoaumento <> 'AU'){
			$mensaje = 'La regla afecta al precio del producto en un valor negativo';
			$error   = true;
		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;
	}


	public function calculo_precio_regla($tipodescuento,$precio,$descuento,$aumentodescuento) {


		// precio regular 



		//calculo entre el producto y la regla
		$calculo = 0;
		if($tipodescuento == 'IMP'){
			if($aumentodescuento == 'AU'){
				$calculo = $precio + $descuento;
			}else{
				$calculo = $precio - $descuento;
			}
		}else{
			if($aumentodescuento == 'AU'){
				$calculo = $precio + $precio * ($descuento/100);
			}else{
				$calculo = $precio - $precio * ($descuento/100);
			}
		}
		return $calculo;

	}


	public function la_regla_esta_desactivada($regla_id,$mensaje) {

		$mensaje					=   $mensaje;
		$error						=   false;
		$cantidad 					=  	0;

		$regla 						=   WEBRegla::where('estado','=','CU')->where('id','=',$regla_id)->get();

		if(count($regla) > 0){
			$mensaje = 'Esta regla esta "CERRADA" no se puede actualizar';
			$error   = true;
		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;

	}



	public function tiene_regla_activa($producto_id,$cliente_id,$contrato_id,$mensaje,$tiporegla) {

		$mensaje					=   $mensaje;
		$error						=   false;
		$cantidad 					=  	0;

		$listareglas = 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglaproductoclientes.regla_id', '=', 'WEB.reglas.id')
						->where('producto_id','=',$producto_id)
						->where('WEB.reglas.tiporegla','=',$tiporegla)
						->where('cliente_id','=',$cliente_id)
						->where('contrato_id','=',$contrato_id)
						->where('WEB.reglaproductoclientes.activo','=','1')
						->get();

		if($tiporegla=='PNC' or $tiporegla=='POV' or $tiporegla=='PRD'){
			$cantidad = 6; //osea si tiene 7 reglas
		}

		if($tiporegla=='NEG'){
			$cantidad = 0; //osea si tiene 2 reglas
		}

		if($tiporegla=='CUP'){
			$cantidad = 0; //osea si tiene 2 reglas
		}


		if(count($listareglas) > $cantidad ){
			$mensaje = 'Tienes una regla activa por el momento';
			$error   = true;
		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;

	}

	public function tiene_regla_repetida_departamento($producto_id,$cliente_id,$contrato_id,$departamento_id_pr,$mensaje,$tipo){

		$mensaje					=   $mensaje;
		$error						=   false;
		$cantidad 					=  	0;
		$departamento_id_pr 		= 	trim($departamento_id_pr);


		$listareglas = 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglaproductoclientes.regla_id', '=', 'WEB.reglas.id')
						->where('WEB.reglaproductoclientes.producto_id','=',$producto_id)
						->where('WEB.reglaproductoclientes.cliente_id','=',$cliente_id)
						->where('WEB.reglaproductoclientes.contrato_id','=',$contrato_id)
						->where('WEB.reglas.departamento_id','=',$departamento_id_pr)						
						->where('WEB.reglaproductoclientes.activo','=','1')
						->get();


		if(count($listareglas) > 0){
			$mensaje = 'Este departamento ya tiene un precio regular';
			$error   = true;
		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;

	}


	public function tiene_regla_repetida($producto_id,$cliente_id,$contrato_id,$regla_id,$mensaje,$tiporegla){

		$mensaje					=   $mensaje;
		$error						=   false;
		$cantidad 					=  	0;

		$listareglas = 	WEBReglaProductoCliente::where('producto_id','=',$producto_id)
						->where('cliente_id','=',$cliente_id)
						->where('contrato_id','=',$contrato_id)
						->where('regla_id','=',$regla_id)						
						->where('activo','=','1')
						->get();

		if(count($listareglas) > 0){
			$mensaje = 'Esta que registra regla repetida';
			$error   = true;
		}								

		$response[] = array(
			'error'           		=> $error,
			'mensaje'      			=> $mensaje
		);

		return $response;

	}




	public function reglas_actualizar_modal($producto_id,$cliente_id,$contrato_id,$tiporegla) {

		$listareglas = 	WEBReglaProductoCliente::join('WEB.reglas', 'WEB.reglaproductoclientes.regla_id', '=', 'WEB.reglas.id')
						->select('WEB.reglaproductoclientes.*')
						->where('producto_id','=',$producto_id)
						->where('WEB.reglas.tiporegla','=',$tiporegla)
						->where('cliente_id','=',$cliente_id)
						->where('contrato_id','=',$contrato_id)
						->where('WEB.reglaproductoclientes.activo','=','1')
						->orderBy('WEB.reglaproductoclientes.activo', 'desc')
						->orderBy('WEB.reglaproductoclientes.fecha_crea', 'desc')
						//->take(5)
						->get();

	 	return  $listareglas;
	}

	public function combo_activas_regla_tipo($tipo,$nombreselect) {


		if($tipo == 'PRD'){

			$lista_activas 		= 	WEBRegla::join('CMP.CATEGORIA', 'COD_CATEGORIA', '=', 'departamento_id')
									->where('activo','=',1)
									->where('tiporegla','=',$tipo)
									->where('estado','=','PU')
									//->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
	    							->where('centro_id','=',Session::get('centros')->COD_CENTRO)
									->select('id', DB::raw("(nombre + ' ' + NOM_CATEGORIA + ' ' + CASE WHEN tipodescuento = 'POR' THEN '%' WHEN tipodescuento = 'IMP' THEN 'S/.' END + CAST(descuento AS varchar(100)) ) AS nombre"))
									->pluck('nombre','id')
									->toArray();			
		}else{

		


        	$cod_centro 		= 	Session::get('centros')->COD_CENTRO;
        	$cod_empresa 		= 	Session::get('empresas')->COD_EMPR;
        	$fecha_actual 	    = 	date('Y-m-d H:i');

			$lista_activas 		= 	WEBRegla::where('activo','=',1)
									->where('tiporegla','=',$tipo)
									->where('estado','=','PU')
									//->where('empresa_id','=',$cod_empresa)
	    							->where('centro_id','=',$cod_centro)
	    							->whereRaw('Convert(varchar(16), fechainicio, 120) <= ?', [$fecha_actual])
	    							->where(function ($query) use ($fecha_actual) {
									    $query->whereRaw('Convert(varchar(16), fechafin, 120) >= ?', [$fecha_actual])
									          ->orWhere('fechafin', '=', '1900-01-01 00:00:00.000');
									})
									->select('id', DB::raw("(nombre + ' ' + CASE WHEN tipodescuento = 'POR' THEN '%' WHEN tipodescuento = 'IMP' THEN 'S/.' END  + CAST(descuento AS varchar(100)) ) AS nombre"))
									->pluck('nombre','id')
									->toArray();


		}




		$comboreglas 		= 	array('' => "Seleccione ".$nombreselect) + $lista_activas;

	 	return  $comboreglas;

	}

	
	public function nombre_producto_seleccionado($idproducto) {

		$nombre 						= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
											->where('producto_id','=',$idproducto)
					    					->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
	    					 				->first();
	 	return    $nombre->NOM_PRODUCTO;					 			
	}


	public function lista_productos_precio_buscar($idproducto,$tipoprecio_id,$contrato_id) {

		if($idproducto != ''){

			$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
											->where('producto_id','=',$idproducto)
					    					->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
	    					 				->orderBy('NOM_PRODUCTO', 'asc')
	    					 				->get();
		}else{


			if($tipoprecio_id == '1'){

				$arrayproducto_id 				= 	WEBPrecioProductoContrato::where('WEB.precioproductocontratos.activo','=','1')
													->where('WEB.precioproductocontratos.ind_contrato','=','1')												
													->where('WEB.precioproductocontratos.empresa_id','=',Session::get('empresas')->COD_EMPR)
													->where('WEB.precioproductocontratos.centro_id','=',Session::get('centros')->COD_CENTRO)
													->where('WEB.precioproductocontratos.contrato_id','=',$contrato_id)
													->pluck('WEB.precioproductocontratos.producto_id')->toArray();


				$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
						    					->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
						    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
						    					->whereIn('producto_id',$arrayproducto_id)
		    					 				->orderBy('NOM_PRODUCTO', 'asc')->get();

			}else{

				$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
						    					->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
						    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
						    					//->whereIn('producto_id',$arrayproducto_id)
		    					 				->orderBy('NOM_PRODUCTO', 'asc')->get();

			}



		}

	 	return    $lista_producto_precio;					 			
	}


	public function producto_buscar($idproducto) {

		$producto 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
	    					->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
	    					->where('centro_id','=',Session::get('centros')->COD_CENTRO)
							->where('producto_id','=',$idproducto)
    					 	->first();

	 	return    $producto;					 			
	}

	public function regla_buscar($regla_id){

		$regla 		= 	WEBRegla::where('id','=',$regla_id)
    					->first();

	 	return    $regla;					 			
	}

	public function cliente_buscar($cliente_id) {

		$cliente 		= 	WEBListaCliente::where('id','=',$cliente_id)
    						->first();

	 	return    $cliente;					 			
	}



	public function lista_productos_precio() {

		$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
					    				->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
					    				->where('centro_id','=',Session::get('centros')->COD_CENTRO)
	    					 			->orderBy('NOM_PRODUCTO', 'asc')->get();
	 	return  $lista_producto_precio;				 			
	}


	public function combo_nombres_lista_productos() {

		$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
						    			->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
						    			->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->pluck('NOM_PRODUCTO','producto_id')
										->take(10)
										->toArray();

		$combolistaproductos  		= 	array('' => "Seleccione producto") + $lista_producto_precio;

	 	return  $combolistaproductos;					 			
	}

	//18-10-2019
	public function combo_lista_empresa() {

		$lista_empresas 			= 	STDEmpresa::where('COD_ESTADO','=','1')
										->where('IND_SISTEMA','=','1')
										->pluck('NOM_EMPR','COD_EMPR')
										->toArray();

		$comboempresas  			= $lista_empresas;

	 	return  $comboempresas;					 			
	}


	//18-10-2019
	public function combo_lista_centro() {

		$lista_centros 				= 	ALMCentro::where('COD_ESTADO','=','1')
										->pluck('NOM_CENTRO','COD_CENTRO')
										->toArray();

		$combocentros  				= array('' => "Seleccione centro") + $lista_centros;

	 	return  $combocentros;					 			
	}



	//18-10-2019
	public function combo_lista_productos() {

		$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
						    			->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
						    			->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->pluck('NOM_PRODUCTO','producto_id')
										->toArray();
		$combolistaproductos  		= 	array('' => "Seleccione producto") + $lista_producto_precio;

	 	return  $combolistaproductos;					 			
	}




	public function combo_lista_productos_todos() {

		$lista_producto_precio 		= 	WEBPrecioProducto::join('WEB.LISTAPRODUCTOSAVENDER', 'COD_PRODUCTO', '=', 'producto_id')
						    			->where('empresa_id','=',Session::get('empresas')->COD_EMPR)
						    			->where('centro_id','=',Session::get('centros')->COD_CENTRO)
										->pluck('NOM_PRODUCTO','producto_id')
										->toArray();
		$combolistaproductos  		= 	array('' => "Seleccione producto",'1' => "TODOS") + $lista_producto_precio;

	 	return  $combolistaproductos;					 			
	}



	public function combo_nombres_lista_clientes() {

		$listaclientes   		=	WEBListaCliente::select('NOM_EMPR')
					    			->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    			->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
									->pluck('NOM_EMPR','NOM_EMPR')
									->take(10)
									->toArray();

		$combolistaclientes  	= 	array('' => "Seleccione clientes") + $listaclientes;
		return $combolistaclientes;					 			
	}






	public function respuestavacio($cliente,$producto_select) {

		if(!is_null($cliente)){
			return false;
		}
		if(!is_null($producto_select)){
			return false;
		}

		return true;
	}

	public function array_id_clientes_top($cantidad){
		$arrayidclientes   			=	WEBListaCliente::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    				->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
										->take($cantidad)->pluck('id')->toArray();
		return $arrayidclientes;
	}

	public function combotipodocumentoxclientes() {

		$arraytipodocumentocliente   	=	WEBListaCliente::select('COD_TIPO_DOCUMENTO','NOM_TIPO_DOCUMENTO')
											->groupBy('COD_TIPO_DOCUMENTO')
											->groupBy('NOM_TIPO_DOCUMENTO')
											->where('COD_TIPO_DOCUMENTO','!=','')
					    					->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
					    					->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
											->pluck('NOM_TIPO_DOCUMENTO','COD_TIPO_DOCUMENTO')
											->toArray();

		$combotipodocumento  			= 	array('' => "Seleccione tipo documento") + $arraytipodocumentocliente;

		return $combotipodocumento;

	}

	public function getUrl($idopcion,$accion) {

	  	//decodificar variable
	  	$decidopcion = Hashids::decode($idopcion);
	  	//ver si viene con letras la cadena codificada
	  	if(count($decidopcion)==0){ 
	  		return Redirect::back()->withInput()->with('errorurl', 'Indices de la url con errores'); 
	  	}

	  	//concatenar con ceros
	  	$idopcioncompleta = str_pad($decidopcion[0], 8, "0", STR_PAD_LEFT); 
	  	//concatenar prefijo

	  	// hemos hecho eso porque ahora el prefijo va hacer fijo en todas las empresas que 1CIX
		//$prefijo = Local::where('activo', '=', 1)->first();
		//$idopcioncompleta = $prefijo->prefijoLocal.$idopcioncompleta;
		$idopcioncompleta = '1CIX'.$idopcioncompleta;

	  	// ver si la opcion existe
	  	$opcion =  WEBRolOpcion::where('opcion_id', '=',$idopcioncompleta)
	  			   ->where('rol_id', '=',Session::get('usuario')->rol_id)
	  			   ->where($accion, '=',1)
	  			   ->first();

	  	if(count($opcion)<=0){
	  		return Redirect::back()->withInput()->with('errorurl', 'No tiene autorización para '.$accion.' aquí');
	  	}
	  	return 'true';

	 }

	public function prefijomaestra() {

		$prefijo = '1CIX';
	  	return $prefijo;
	}

	public function getCreateIdMaestra($tabla) {

  		$id="";

  		// maximo valor de la tabla referente
		$id = DB::table($tabla)
        ->select(DB::raw('max(SUBSTRING(id,5,8)) as id'))
        ->get();

        //conversion a string y suma uno para el siguiente id
        $idsuma = (int)$id[0]->id + 1;

	  	//concatenar con ceros
	  	$idopcioncompleta = str_pad($idsuma, 8, "0", STR_PAD_LEFT);

	  	//concatenar prefijo
		$prefijo = $this->prefijomaestra();

		$idopcioncompleta = $prefijo.$idopcioncompleta;

  		return $idopcioncompleta;	

	}

	public function decodificarmaestra($id) {

	  	//decodificar variable
	  	$iddeco = Hashids::decode($id);
	  	//ver si viene con letras la cadena codificada
	  	if(count($iddeco)==0){ 
	  		return ''; 
	  	}
	  	//concatenar con ceros
	  	$idopcioncompleta = str_pad($iddeco[0], 8, "0", STR_PAD_LEFT); 
	  	//concatenar prefijo

		//$prefijo = Local::where('activo', '=', 1)->first();

		// apunta ahi en tu cuaderno porque esto solo va a permitir decodifcar  cuando sea el contrato del locl en donde estas del resto no 
		//¿cuando sea el contrato del local?
		$prefijo = $this->prefijomaestra();
		$idopcioncompleta = $prefijo.$idopcioncompleta;
	  	return $idopcioncompleta;

	}


	public function decodificarid($id,$prefijo) {

	  	//decodificar variable
	  	$iddeco = Hashids::decode($id);
	  	//ver si viene con letras la cadena codificada
	  	if(count($iddeco)==0){ 
	  		return ''; 
	  	}
	  	//concatenar con ceros
	  	$idopcioncompleta = str_pad($iddeco[0], 13, "0", STR_PAD_LEFT); 
	  	//concatenar prefijo
		$idopcioncompleta = $prefijo.$idopcioncompleta;
	  	return $idopcioncompleta;

	}

	public function codecupon(){
	  	return Hashids::encode(Keygen::numeric(10)->generate());
	}


	public function NotificarEstadoPedido($idpedido){
		$pedido 						=   WEBPedido::where('id','=',$idpedido)->first();
		$vendedor = $this->data_usuario($pedido->usuario_crea);

		switch ($pedido->estado_id) {
			case 'EPP0000000000002':
				$wm    = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00005')->first();
				
				$sms="Un nuevo pedido ".$pedido->codigo." fue generado correctamente.";
				break;
			case 'EPP0000000000003':
				$wm    = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00006')->first();
				$sms="El pedido ".$pedido->codigo." fue autorizado correctamente.";
				
				break;
			case 'EPP0000000000004':
				$wm    = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00007')->first();
				$sms="El pedido ".$pedido->codigo." fue ejecutado correctamente.";
				break;
			case 'EPP0000000000005':
				$wm    = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00006')->first();
				$sms="El pedido ".$pedido->codigo." ha sido rechazado.";
			    break;

		}

		return $this->SendSMS($wm->gsm.','.$vendedor->gsm,$sms);
    }

	public function SendSMS($gsm,$sms){

    $apikey = "55A2B819FF77";
    $apicard = "7050509039";
	$fields_string = "";
	$smstype = "0"; // 0: remitente largo, 1: remitente corto


    $smsnumber = $gsm;
    $smstext = $sms;
  

    //Preparamos las variables que queremos enviar
    $url = 'http://api2.gamacom.com.pe/smssend'; // Para HTTPS $url = 'https://api3.gamanet.pe/smssend'; 
    $fields = array(
                        'apicard'=>urlencode($apicard),
                        'apikey'=>urlencode($apikey),
                        'smsnumber'=>urlencode($smsnumber),
                        'smstext'=>urlencode($smstext),
                        'smstype'=>urlencode($smstype)
                );

    //Preparamos el string para hacer POST (formato querystring)
    foreach($fields as $key=>$value) { 
       $fields_string .= $key.'='.$value.'&'; 
    }
    $fields_string = rtrim($fields_string,'&');


    //abrimos la conexion
    $ch = curl_init();

    //configuramos la URL, numero de variables POST y los datos POST
    curl_setopt($ch,CURLOPT_URL,$url);
    //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); //Descomentarlo si usa HTTPS
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

    //ejecutamos POST
    $result = curl_exec($ch);

    //cerramos la conexion
    curl_close($ch);

    //Resultado
    $array = json_decode($result,true);

	return "error:".$array["message"]."uniqueid:".$array["uniqueid"];
          
  }


}

