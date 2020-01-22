<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />

        <style>
            .mensaje{
                margin: 0 auto;
                width: 700px;
                text-align: center;
            }
            .mensaje p{
                text-align: center;
            }
            .mensaje .fc{
                color: #50B948;
                font-size: 24px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
    	<section>
    
            <table style="width:100%;font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                            <br>
                             <tr>
                             <td  style=  "font-size:14px;text-align:justify">Observación:</td>
                             </tr>
                             <tr>
                             <td  style=  "font-size:14px;font-weight:bold;text-align:justify">{{ $NP->glosa }} </td>
                             </tr>
                             <tr></tr>
                             <tr></tr>
                             </tbody>
                             </table>
                             <table width=  "100%"  style=  "font-family:Calibri, Candara, Segoe, Optima, Arial, sans - serif"  >
                             <tbody>
                             <tr>
                             <td align=  "center">
                             <table width=  "100%"  bgcolor=  "indianred"  style =  "font-family: Calibri, Candara, Segoe, Optima, Arial, sans - serif"   cellspacing=  "0"  cellpadding=  "0">
                             <tbody>
                             <tr>
                             <td  colspan=  "2"  align=  "center"  style=  "padding: 5px 0px 3px 9px;font-size:13px;color:white;font-weight:bolder"> NOTA DE PEDIDO: {{ $NP->codigo }} 
                             </td>
                             </tr>
                             </tbody>
                             </table>
                             </td>
                             </tr>
                             <tr>
                             <td align=  "center">
                             <table width=  "100%"  bgcolor=  "#f2f2f2">
                             <tbody>
                             <tr>
                             <td>
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">  
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Solicitud: </td> 
                             <td style="font-size:13px;color:#191970"> {{$vendedor->nombre}}</td>
                             </tr>
                             </tbody>
                             </table>  
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">  
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Cliente: </td> 
                             <td style="font-size:13px;color:#191970"> {{$NP->empresa->NOM_EMPR}}</td>
                             </tr>
                             </tbody>
                             </table> 
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">  
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Condición: </td> 
                             <td style="font-size:13px;color:#191970"> {{$NP->condicionpago->NOM_CATEGORIA}} </td>
                             </tr>
                              </tbody>
                             </table>  
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold"> Fecha Entrega:     </td>
                             <td style="font-size:13px;color:#191970">{{$NP->fecha_despacho}}</td>
                             </tr>
                             </tbody>
                             </table>
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Dirección Entrega:  </td>
                             <td style="font-size:13px;color:#191970">{{$NP->direccionentrega->NOM_DIRECCION}}</td>
                             </tr>
                             </tbody>
                             </table>
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Valorizado:  </td>
                             <td style="font-size:13px;color:#191970"> {{$NP->total}} </td>
                             </tr>
                             </tbody>
                             </table>

                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>  
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Deuda:  </td>
                             <td style="font-size:13px;color:#191970"> 
                                <?php $sum = 0; ?>
                                    @foreach($saldo as $it)
                                <?php $sum += $it->SALCON; ?>
                                    @endforeach
                                {{$sum}}
                             </td>
                             </tr>
                             </tbody>
                             </table>

                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Limite de credito:  </td>
                             <td style="font-size:13px;color:#191970"> 
                                @if(count($limite_credito)>0) 
                                    {{$limite_credito->canlimitecredito}}
                                @else
                                    -    
                                @endif
                             </td>
                             </tr>
                             </tbody>
                             </table>


                             <table style=  "margin-top:5px;font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>  
                                 <td width=  "100"  style=  "font-size:13px;font-weight:bold;text-align: center;" Colspan ='2'>  Venta mas antigua por pagar:  </td>
                             </tr>
                             <tr>  
                                 <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Documento:  </td>
                                 <td style="font-size:13px;color:#191970">
                                    @foreach($deuda_antigua as $deu)
                                        {{$deu->NroDocumento}}
                                    @endforeach
                                 </td>
                             </tr>
                             <tr>  
                                 <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Días transcurridos :  </td>
                                 <td style="font-size:13px;color:#191970">
                                    @foreach($deuda_antigua as $deu)
                                        {{$deu->diasTranscurridos}}
                                    @endforeach
                                 </td>
                             </tr>
                             <tr>  
                                 <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Saldo a pagar:  </td>
                                 <td style="font-size:13px;color:#191970">
                                    @foreach($deuda_antigua as $deu)
                                        {{$deu->CAN_SALDO}}
                                    @endforeach
                                 </td>
                             </tr>
                             </tbody>
                             </table>



                             </td>
                             </tr>
                             </tbody>
                             </table>
                             </td>
                             </tr>
                             <tr></tr>
                             <tr></tr>
                             </tbody>
                             </table>
                             <table style="width:100%;font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif" bgcolor=  "#f2f2f2">
                             <tbody>
                             <tr><td width=  "100"  style=  "font-size:13px;font-weight:bold">  Detalle :  </td></tr>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold" colspan="1"> Producto </td> 
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold" colspan="1"> Cantidad </td> 
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold" colspan="1"> PU </td> 
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold" colspan="1"> Total </td> 
                             </tr>
                            @foreach ( $detalle as $item)
                                 <tr>    
                                 <td width=  "100"  style=  "font-size:13px;font-weight:bold" colspan="1"> {{$item->producto->NOM_PRODUCTO}} </td> 
                                 <td style="font-size:13px;color:#191970">{{$item->cantidad}} </td>
                                 <td style="font-size:13px;color:#191970"> {{$item->precio}} </td>
                                 <td style="font-size:13px;color:#191970"> {{$item->total}} </td>
                                 </tr>
                            @endforeach 
                             <tbody><tr>
                             <td> </td>

                             <td width="300"></td>
                             </tr>
                             </tbody></table>
                             </td> 
                             </tr>
                             </tbody>
                             </table>
        
        
        </section>
    </body>
</html>


