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
                             <td style="font-size:13px;color:#191970">{{$direccion->NOM_DIRECCION}} - {{$direccion->distrito->NOM_CATEGORIA}}</td>
                             </tr>
                             </tbody>


                             </table>
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Valorizado:  </td>
                             <td style="font-size:13px;color:#191970"> {{number_format($NP->total, 2, '.', ',')}}</td>
                             </tr>
                             </tbody>
                             </table>
                             
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>  
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Deuda:  </td>
                                 <td style="font-size:13px;color:#191970"> 
                                        @foreach($saldo as $it)
                                            <p style=  "padding:2px;margin: 2px;"><strong>{{$it->EMPRESA}} :</strong>{{number_format($it->SALCON, 2, '.', ',')}}</p> 
                                        @endforeach
                                 </td>
                             </tr>

                             </table>
                             </tbody>
                             <table style=  "font-family:Calibri,Candara,Segoe,Optima,Arial,sans-serif">
                             <tbody>
                             <tr>    
                             <td width=  "100"  style=  "font-size:13px;font-weight:bold">  Limite de credito:  </td>
                             <td style="font-size:13px;color:#191970"> 
                                @if(count($limite_credito)>0) 
                                    {{number_format($limite_credito->canlimitecredito, 2, '.', ',')}}
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
                                     <td width=  "100"  style=  "font-size:13px;font-weight:bold;text-align: center;" colspan="4">  Venta mas antigua por pagar:  </td>
                                 </tr>
                                @php 
                                    $sfi =  0;
                                    $shr =  0;
                                    $sia =  0;
                                    $sic =  0;
                                    $sii =  0;
                                    $sin =  0;
                                    $sis =  0;
                                    $sit =  0;
                                @endphp

                                @foreach($deuda_antigua as $deu)
                                    @if($deu->NOM_EMPR == 'FI' && $sfi ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sfi =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'HR' && $shr ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $shr =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'IA' && $sia ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sia =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'IC' && $sic ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sic =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'II' && $sii ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sii =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'IN' && $sin ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sin =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'IS' && $sis ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sis =  1; @endphp
                                    @endif

                                    @if($deu->NOM_EMPR == 'IT' && $sit ==0)
                                        <tr> 
                                            <td style=  "font-size:13px;"><strong>Empresa:</strong> <small style="font-size:13px;color:#191970">{{$deu->NOM_EMPR}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Documento:</strong> <small style="font-size:13px;color:#191970">{{$deu->NroDocumento}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Días transcurrido:</strong> <small style="font-size:13px;color:#191970">{{$deu->diasTranscurridos}}</small></td>
                                            <td style=  "font-size:13px;"><strong>Saldo a pagar:</strong> <small style="font-size:13px;color:#191970">{{number_format($deu->CAN_SALDO, 2, '.', ',')}}</small></td>
                                        </tr>
                                        @php $sit =  1; @endphp
                                    @endif

                                @endforeach
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


