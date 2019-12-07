<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\CMPContrato;
use View;
use Session;
use Hashids;


class GeneralAjaxController extends Controller
{


	// cambio

	public function actionCanalResponsable(Request $request)
	{


		$responsable_id					= 	$request['responsable_id'];

		if($responsable_id == '1'){

			$lista_canales_responsable		=	CMPContrato::leftjoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.CONTRATO.COD_CATEGORIA_TIPO_CONTRATO')
												->leftjoin('STD.EMPRESA', 'STD.EMPRESA.COD_EMPR', '=', 'CMP.CONTRATO.COD_EMPR_CLIENTE')
												->where('CMP.CONTRATO.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
												->where('CMP.CONTRATO.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
												->whereNotIn('CMP.CONTRATO.COD_CATEGORIA_ESTADO_CONTRATO',['ECO0000000000005' ,'ECO0000000000006'])
												//->where('CMP.CONTRATO.COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
												->where('CMP.CATEGORIA.TXT_ABREVIATURA','=','CON')
												->pluck('CMP.CONTRATO.TXT_CATEGORIA_CANAL_VENTA','CMP.CONTRATO.COD_CATEGORIA_CANAL_VENTA')
												->toArray();
									

		}else{

			$lista_canales_responsable		=	CMPContrato::leftjoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.CONTRATO.COD_CATEGORIA_TIPO_CONTRATO')
												->leftjoin('STD.EMPRESA', 'STD.EMPRESA.COD_EMPR', '=', 'CMP.CONTRATO.COD_EMPR_CLIENTE')
												->where('CMP.CONTRATO.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
												->where('CMP.CONTRATO.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
												->whereNotIn('CMP.CONTRATO.COD_CATEGORIA_ESTADO_CONTRATO',['ECO0000000000005' ,'ECO0000000000006'])
												->where('CMP.CONTRATO.COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
												->where('CMP.CATEGORIA.TXT_ABREVIATURA','=','CON')
												->pluck('CMP.CONTRATO.TXT_CATEGORIA_CANAL_VENTA','CMP.CONTRATO.COD_CATEGORIA_CANAL_VENTA')
												->toArray();	
		}



							   
		$combo_canal  					= 	array('' => "Seleccione Canal") + $lista_canales_responsable;


		return View::make('general/ajax/combocanal',
						 [
						 	'combo_canal' => $combo_canal
						 ]);
	}	



	public function actionClienteResponsable(Request $request)
	{


		$responsable_id					= 	$request['responsable_id'];

		if($responsable_id == '1'){

			$lista_clientes_responsable		=		CMPContrato::leftjoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.CONTRATO.COD_CATEGORIA_TIPO_CONTRATO')
													->leftjoin('STD.EMPRESA', 'STD.EMPRESA.COD_EMPR', '=', 'CMP.CONTRATO.COD_EMPR_CLIENTE')
													->where('CMP.CONTRATO.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
													->where('CMP.CONTRATO.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
													->whereNotIn('CMP.CONTRATO.COD_CATEGORIA_ESTADO_CONTRATO',['ECO0000000000005' ,'ECO0000000000006'])
													//->where('CMP.CONTRATO.COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
													->where('CMP.CATEGORIA.TXT_ABREVIATURA','=','CON')
													->pluck('CMP.CONTRATO.TXT_EMPR_CLIENTE','CMP.CONTRATO.COD_CONTRATO')
													->toArray();
									

		}else{

			$lista_clientes_responsable		=	CMPContrato::leftjoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.CONTRATO.COD_CATEGORIA_TIPO_CONTRATO')
												->leftjoin('STD.EMPRESA', 'STD.EMPRESA.COD_EMPR', '=', 'CMP.CONTRATO.COD_EMPR_CLIENTE')
												->where('CMP.CONTRATO.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
												->where('CMP.CONTRATO.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
												->whereNotIn('CMP.CONTRATO.COD_CATEGORIA_ESTADO_CONTRATO',['ECO0000000000005' ,'ECO0000000000006'])
												->where('CMP.CONTRATO.COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
												->where('CMP.CATEGORIA.TXT_ABREVIATURA','=','CON')
												->pluck('CMP.CONTRATO.TXT_EMPR_CLIENTE','CMP.CONTRATO.COD_CONTRATO')
												->toArray();
						
		}

		$combo_cliente  					= 	array('' => "Seleccione Cliente") + $lista_clientes_responsable;


		return View::make('general/ajax/combocliente',
						 [
						 	'combo_cliente' => $combo_cliente
						 ]);
	}	




	public function actionSubCanalCanalResponsable(Request $request)
	{


		$responsable_id								= 	$request['responsable_id'];
		$canal_id									= 	$request['canal_id'];

		if($responsable_id == '1'){

			$lista_subcanales_canales_responsable		=	CMPContrato::leftjoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.CONTRATO.COD_CATEGORIA_TIPO_CONTRATO')
															->leftjoin('STD.EMPRESA', 'STD.EMPRESA.COD_EMPR', '=', 'CMP.CONTRATO.COD_EMPR_CLIENTE')
															->where('CMP.CONTRATO.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
															->where('CMP.CONTRATO.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
															->whereNotIn('CMP.CONTRATO.COD_CATEGORIA_ESTADO_CONTRATO',['ECO0000000000005' ,'ECO0000000000006'])
															//->where('CMP.CONTRATO.COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
															->where('CMP.CONTRATO.COD_CATEGORIA_CANAL_VENTA','=',$canal_id)
															->where('CMP.CATEGORIA.TXT_ABREVIATURA','=','CON')
															->pluck('CMP.CONTRATO.TXT_CATEGORIA_SUB_CANAL','CMP.CONTRATO.COD_CATEGORIA_SUB_CANAL')
															->toArray();
									

		}else{


			$lista_subcanales_canales_responsable		=	CMPContrato::leftjoin('CMP.CATEGORIA', 'CMP.CATEGORIA.COD_CATEGORIA', '=', 'CMP.CONTRATO.COD_CATEGORIA_TIPO_CONTRATO')
															->leftjoin('STD.EMPRESA', 'STD.EMPRESA.COD_EMPR', '=', 'CMP.CONTRATO.COD_EMPR_CLIENTE')
															->where('CMP.CONTRATO.COD_EMPR','=',Session::get('empresas')->COD_EMPR)
															->where('CMP.CONTRATO.COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
															->whereNotIn('CMP.CONTRATO.COD_CATEGORIA_ESTADO_CONTRATO',['ECO0000000000005' ,'ECO0000000000006'])
															->where('CMP.CONTRATO.COD_CATEGORIA_JEFE_VENTA','=',$responsable_id)
															->where('CMP.CONTRATO.COD_CATEGORIA_CANAL_VENTA','=',$canal_id)
															->where('CMP.CATEGORIA.TXT_ABREVIATURA','=','CON')
															->pluck('CMP.CONTRATO.TXT_CATEGORIA_SUB_CANAL','CMP.CONTRATO.COD_CATEGORIA_SUB_CANAL')
															->toArray();
		}					   

		$combo_sub_canal  							= 	array('' => "Seleccione Sub Canal") + $lista_subcanales_canales_responsable;


		return View::make('general/ajax/combosubcanal',
						 [
						 	'combo_sub_canal' => $combo_sub_canal
						 ]);
	}	



}
