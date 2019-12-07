<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\WEBListaCliente,App\STDTipoDocumento,App\WEBReglaProductoCliente,App\WEBPedido;
use App\WEBDetallePedido,App\CMPCategoria,App\WEBReglaCreditoCliente,App\STDEmpresa,App\WEBPrecioProducto,App\WEBMaestro,App\WEBPrecioProductoContrato,App\STDEmpresaDireccion;
use View;
use Session;
use App\Biblioteca\Osiris;
use App\Biblioteca\Funcion;
use PDO;
use Mail;
use PDF;
  
class OrdenPedidoReporteController extends Controller
{


	public function actionImprimirPedido($idpedido)
	{


		$titulo 									=   'Pedido';
		$idpedido 									= 	$this->funciones->desencriptar_id('1CIX-'.$idpedido,8);
		$pedido 									=   WEBPedido::where('id','=',$idpedido)->first();
		$funcion 									= 	$this;


		$pdf 										= 	PDF::loadView('pedido.pdf.imprimirpedido', 
														[
															'pedido' 	=> $pedido,
															'titulo' 	=> $titulo,
															'funcion' 	=> $funcion								
														]);

		return $pdf->stream('download.pdf');


	}




}
