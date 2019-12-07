<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	{!! Html::style('public/css/excel/excel.css') !!}

    <!-- titulo -->
    <table>
        <tr>
            <td class = 'subtitulos' colspan="4">{{$empresa}} - {{$centro}}</td>                    
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <th class= 'center tabladp' colspan="2">DATOS</th>
            <th class= 'center tablamar' colspan="2">REGLAS</th>  
        </tr>

        <tr>
            <th class= 'tabladp'>CLIENTE</th>
            <th class= 'tabladp'>PRODUCTO</th>
            <th class= 'center tablamar'>PRECIO PRODUCTO</th>              
            <th class= 'center warning'>NOTA DE CREDITO</th> 
        </tr>

        @foreach($listacliente as $index_c => $item_c) 
            @foreach($listadeproductos as $index => $item) 

                <!-- REGLAS DE LOS CLIENTES-->
                @php
                  $lista_reglas_cliente    =   $funcion->funciones->lista_reglas_cliente($item_c->COD_CONTRATO,$item->producto_id);
                @endphp

                @if(count($lista_reglas_cliente)>0) 

                    <tr>
                        <td>{{$item_c->NOM_EMPR}}</td>
                        <td>{{$item->NOM_PRODUCTO}}</td>
                        <td>

                              @foreach($lista_reglas_cliente as $index => $item)
                                @if ($item->tiporegla == 'POV') 
                                    @if($item->tipodescuento == 'POR') 
                                      %
                                    @else 
                                      S/.
                                    @endif
                                    {{number_format($item->descuento, 4, '.', ',')}} |
                                @endif
                              @endforeach

                        </td>
                        <td>

                              @foreach($lista_reglas_cliente as $index => $item)
                                @if ($item->tiporegla == 'PNC') 
                                    @if($item->tipodescuento == 'POR') 
                                      %
                                    @else 
                                      S/.
                                    @endif
                                    {{number_format($item->descuento, 4, '.', ',')}} |
                                @endif
                              @endforeach


                        </td>
                    </tr>
                @else 
                    <tr>
                        <td>{{$item_c->NOM_EMPR}}</td>
                        <td>{{$item->NOM_PRODUCTO}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif

            @endforeach
        @endforeach

    </table>
</html>
