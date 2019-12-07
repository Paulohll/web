<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Biblioteca\NotaCredito;
use App\STDTrabajador;
use App\WEBLISTASERIE;
use Session;
use View;

class DetraccionController extends Controller
{
    public function index($idopcion)
    {

        /******************* validar url **********************/
        $validarurl = $this->funciones->getUrl($idopcion, 'Anadir');
        if ($validarurl <> 'true') {
            return $validarurl;
        }
        /******************************************************/



        return View::make('contenido/gestionDetraccion');
    }


    public function getOrden(Request $request)
    {

        //  header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        // header('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With');
        // header('Access-Control-Allow-Credentials',' true');

        if (!$request->ajax()) return redirect('/');
        $page = request('page', 1);
        $fecini =$request->fecini;
        $fecfin = $request->fecfin;
        $empresa = $request->empresa;
        $centro = $request->centro;
        $estado=$request->estado;
 
        // ->paginate(10);
        // $pageSize = 25;
        $orden = DB::select('SET NOCOUNT ON ; exec WEB.GESTION_DETRACCION ?,?,?,?,?,?,?,?,?,?,?,?', array('GOD',$empresa,$centro,$fecini,$fecfin,'','','','','','',$estado));
        // $offset = ($page * $pageSize) - $pageSize;
        // $data = array_slice($orden, $offset, $pageSize, true);
        // $paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, count($orden), $pageSize, $page);

        return [
            // 'pagination' => [
            //     'total'        => $paginator->total(),
            //     'current_page' => $paginator->currentPage(),
            //     'per_page'     => $paginator->perPage(),
            //     'last_page'    => $paginator->lastPage(),
            //     'from'         => $paginator->firstItem(),
            //     'to'           => $paginator->lastItem(),
            // ],
            'obj' => $orden
        ];
    }

    public function GetSerie(Request $request) {
        if (!$request->ajax()) return redirect('/');
        $tipodocumento =$request->tipodocumento;

        $trabajador_sin_sede    =       STDTrabajador::where('COD_TRAB','=',Session::get('usuario')->usuarioosiris_id)
                                        ->where('COD_ESTADO','=',1)->first();


        $trabajador             =       STDTrabajador::where('NRO_DOCUMENTO','=',$trabajador_sin_sede->NRO_DOCUMENTO)
                                        ->where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
                                        ->where('COD_ESTADO','=',1)->first();


        $lista_series           =   WEBLISTASERIE::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
                                    ->where('COD_CENTRO','=',Session::get('centros')->COD_CENTRO)
                                    ->where('COD_TRAB','=',$trabajador->COD_TRAB)
                                    -> where('COD_CATEGORIA_TIPO_DOCUMENTO','=',$tipodocumento)
                                    ->get();
                                    // ->pluck('NRO_SERIE','NRO_SERIE')
                                    // ->toArray();
                               
        // $combo_series       =   array('' => "Seleccione Serie") + $lista_series;
        
        return [
            // 'pagination' => [
            //     'total'        => $paginator->total(),
            //     'current_page' => $paginator->currentPage(),
            //     'per_page'     => $paginator->perPage(),
            //     'last_page'    => $paginator->lastPage(),
            //     'from'         => $paginator->firstItem(),
            //     'to'           => $paginator->lastItem(),
            // ],
            'serie' => $lista_series
        ];       
    }

    public function ProcesarPagoDetraccion(Request $request)
    {

        //  header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        // header('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With');
        // header('Access-Control-Allow-Credentials',' true');

        if (!$request->ajax()) return redirect('/');
        $empresa = $request->empresa;
        $centro = $request->centro;
        $codigo = $request->codigo;
        $codempr_emisor = $request->codempr_emisor;
        $codempr_receptor = $request->codempr_receptor;
        $cod_doc_serie = $request->cod_doc_serie;
        $cod_direccion_origen = $request->cod_direccion_origen;
        $cod_direccion_destino = $request->cod_direccion_destino;
        // ->paginate(10);
        // $pageSize = 25;
        
        $odata = DB::select('SET NOCOUNT ON ; exec WEB.GESTION_DETRACCION ?,?,?,?,?,?,?,?,?,?,?,?', array('PAG',$empresa,$centro,'','',$codigo,$codempr_emisor,$codempr_receptor, $cod_doc_serie, $cod_direccion_origen, $cod_direccion_destino,0));
        // $offset = ($page * $pageSize) - $pageSize;
        // $data = array_slice($orden, $offset, $pageSize, true);
        // $paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, count($orden), $pageSize, $page);

        return [
            // 'pagination' => [
            //     'total'        => $paginator->total(),
            //     'current_page' => $paginator->currentPage(),
            //     'per_page'     => $paginator->perPage(),
            //     'last_page'    => $paginator->lastPage(),
            //     'from'         => $paginator->firstItem(),
            //     'to'           => $paginator->lastItem(),
            // ],
            'obj' => $odata
        ];
    }
}
