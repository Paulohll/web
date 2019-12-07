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
            <th class= 'center tablaho' colspan="4">PRECIO</th>  
        </tr>

        <tr>
            <th class= 'tabladp'>CLIENTE</th>
            <th class= 'tabladp'>PRODUCTO</th>
            <th class= 'center tablaho'>DEPARTAMENTO</th>              
            <th class= 'center tablaho'>PRECIO REGULAR</th> 
            <th class= 'center tablaho'>DESCUENTO</th>
            <th class= 'center tablaho'>PRECIO TOTAL</th> 
        </tr>

        @foreach($listacliente as $index_c => $item_c) 
            @foreach($listadeproductos as $index => $item) 

                <!-- PRECIOS CON DEPARTAMENTOS-->
                @php
                  $lista_precio_regular_departamento    =   $funcion->funciones->lista_precio_regular_departamento($item_c->COD_CONTRATO,$item->producto_id);
                @endphp

                @if(($index % 2) == 0 ) 
                    @php  $color = 'tablafila1'; @endphp
                @else 
                    @php  $color = 'tablafila2'; @endphp
                @endif

                <tr>
                    <td width="50" class='{{$color}}'> {{$item_c->NOM_EMPR}}</td>
                    <td width="50" class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>
                    <td width="20" class='negrita {{$color}}'>OTROS</td>
                    <td width="20" class='negrita {{$color}}'>
                        S/. {{$funcion->funciones->calculo_precio_regular($item_c,$item)}}
                    </td>
                    <td width="20" class='negrita {{$color}}'>
                        S/. {{$funcion->funciones->descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,'')}}
                    </td>
                    <td width="20" class='negrita {{$color}}'>
                        S/. {{$funcion->funciones->precio_descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,'')}}
                    </td> 

                </tr>
                @foreach($lista_precio_regular_departamento as $index_pr => $item_pr)
                <tr>
                    <td width="50" class='{{$color}}'> {{$item_c->NOM_EMPR}}</td>
                    <td width="50" class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>
                    <td width="20" class='negrita {{$color}}'>{{$funcion->funciones->departamento($item_pr->departamento_id)->NOM_CATEGORIA}}</td> 
                    <td width="20" class='right negrita {{$color}}'> S/. {{number_format($item_pr->descuento, 2, '.', ',')}}</td>
                    <td width="20" class='right negrita {{$color}}'> 
                        S/. {{$funcion->funciones->descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,$item_pr->departamento_id)}}
                    </td>
                    <td width="20" class='right negrita {{$color}}'> 
                        S/. {{$funcion->funciones->precio_descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,$item_pr->departamento_id)}}
                    </td>
                </tr>     
                @endforeach  

            @endforeach
        @endforeach

    </table>
</html>
