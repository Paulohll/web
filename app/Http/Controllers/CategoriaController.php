<?php

namespace App\Http\Controllers;

use App\ALMCentro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\CMPCategoria;
use App\STDEmpresa;
use Session;

class CategoriaController extends Controller
{
    public function ListarCategoria(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $grupo = $request->buscar;
        switch ($grupo) {
            case "JEFE_VENTA":
                    $categoria = CMPCategoria::from('CMP.CATEGORIA AS CA')
                    ->select('CA.COD_CATEGORIA', 'CA.NOM_CATEGORIA')
                    ->where('CA.COD_ESTADO','=',1)
                    ->where('IND_ACTIVO','=',1)
                    ->where('CA.TXT_GRUPO', '=' , $grupo)
                    ->where('CA.TXT_ABREVIATURA','=', Session::get('centros')->COD_CENTRO)
                    ->get();
                    
                    return [
                        
                        'categoria' => $categoria
                    ];
                break;
            case "EMPRESA":
                    $categoria = STDEmpresa::from('STD.EMPRESA AS E')
                    ->select('E.NOM_EMPR', 'E.COD_EMPR')
                    ->where('E.COD_ESTADO','=',1)
                    ->where('E.IND_SISTEMA','=',1)
                    ->get();
                    
                    return [
                        
                        'categoria' => $categoria
                    ];
            case "CENTRO":
                    $categoria = ALMCentro::from('ALM.CENTRO AS C')
                    ->select('C.NOM_CENTRO', 'C.COD_CENTRO')
                    ->where('C.COD_ESTADO','=',1)
                    ->get();
                    
                    return [
                        
                        'categoria' => $categoria
                    ];
            case "MOTIVO_TRASLADO":
                    $categoria = CMPCategoria::from('CMP.CATEGORIA AS CA')
                    ->select('CA.COD_CATEGORIA', 'CA.NOM_CATEGORIA')
                    ->where('CA.COD_ESTADO','=',1)
                    ->where('CA.TXT_GRUPO', '=' , $grupo)
                    ->get();
                    return [
                        
                        'categoria' => $categoria
                    ];
             case "EMPRESA_DIRECCION":
                    $categoria = CMPCategoria::from('STD.EMPRESA_DIRECCION AS ED')
                    ->select('ED.NOM_DIRECCION', 'ED.COD_DIRECCION')
                    ->where('ED.COD_ESTADO','=',1)
                    ->where('ED.COD_EMPR', '=' , 'IACHEM0000010394')
                    ->get();
                    return [
                        
                        'categoria' => $categoria
                    ];
            default:
                    $categoria = CMPCategoria::from('CMP.CATEGORIA AS CA')
                    ->select('CA.COD_CATEGORIA', 'CA.NOM_CATEGORIA')
                    ->where('CA.COD_ESTADO','=',1)
                    ->where('CA.TXT_GRUPO', '=' , $grupo)
                    ->get();
                    
                    return [
                        
                        'categoria' => $categoria
                    ];
        }

      
    }
}
