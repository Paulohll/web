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
            <th class= 'center tabladp'>DATOS</th>
            <th class= 'center tablaho' colspan="3">PRECIO ({{$fechafin}})</th>
            <th class= 'center tablaho'  >PROMOCION</th>  
        </tr>

        <tr>
            <th class= 'tabladp'>PRODUCTO</th>             
            <th class= 'center tablaho'>MPSA</th> 
            <th class= 'center tablaho'>OML</th>
            <th class= 'center tablaho'>DISTL</th> 
            <th class= 'center tablaho'>Reglas</th>
        </tr>

      @foreach($listadeproductos as $index => $item) 
                <tr>


                @php
                  $precio_regular_mpsa          =   0.0000;
                  $precio_regular_oml           =   0.0000;
                  $precio_regular_dist          =   0.0000;

                  $reglas                       =   '';
                  $precio_regular_mpsa          =   $funcion->funciones->calculo_precio_regular_fecha_subcanal('SCV0000000000004',$item,$fechafin);
                  $precio_regular_oml           =   $funcion->funciones->calculo_precio_regular_fecha_subcanal('SCV0000000000020',$item,$fechafin);
                  $precio_regular_dist          =   $funcion->funciones->calculo_precio_regular_fecha_subcanal('SCV0000000000005',$item,$fechafin);
                  $reglas                       =   $funcion->funciones->reglas_producto_fecha_sub_canales($item->producto_id,$fechafin);
                @endphp

                @if(($index % 2) == 0 ) 
                    @php  $color = 'tablafila1'; @endphp
                @else 
                    @php  $color = 'tablafila2'; @endphp
                @endif


                    <td width="50" class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>
                    <td width="20" class='negrita {{$color}}'>
                          S/. {{$precio_regular_mpsa}}
                    </td>
                    <td width="20" class='negrita {{$color}}'>
                          S/. {{$precio_regular_oml}}
                    </td>
                    <td width="20" class='negrita {{$color}}'>
                          S/. {{$precio_regular_dist}}
                    </td>
                    <td width="80" class='negrita {{$color}}'>
                          {{$reglas}}
                    </td>

                </tr>
      @endforeach       

    </table>
</html>
