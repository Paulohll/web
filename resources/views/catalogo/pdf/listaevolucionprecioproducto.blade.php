<!DOCTYPE html>
<html lang="es">

<head>
  <title>{{$titulo}}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('public/css/pdf.css') }} "/>

</head>
<body>
    <header>
      <div class='reporte'>
        <h3 class="center titulo">{{$empresa}} - {{$centro}}</h3>
        <p class="subtitulo">
          <strong class='fecha'>Día : {{date_format(date_create($fechafin), 'd-m-Y')}}</strong>
        </p>


      </div>
    </header>
    <section>
        <article>
          <table>
            <tr>
                <th colspan="2" class='titulotabla center tabladp'>DATOS</th>     
                <th colspan="3" class='titulotabla center tablaho'>PRECIO</th>
                <th class='titulotabla center tablaho' >VENTA</th>   
            </tr>

            <tr>
                <th width="140" class= 'tabladp'>CLIENTE</th>
                <th width="140" class= 'tabladp'>PRODUCTO</th>
             
                <th width="10" class= 'titulotabla tablaho'>PRECIO REGULAR</th> 
                <th width="10" class= 'titulotabla tablaho'>DESCUENTO</th>
                <th width="10" class= 'titulotabla tablaho'>PRECIO TOTAL</th> 
                <th width="10" class= 'center tablaho'>ORDEN CEN</th>
            </tr>

	        @foreach($listacliente as $index_c => $item_c) 
	            @foreach($listadeproductos as $index => $item) 



                @php
                  $precio_regular    =   0.0000;
                  $descuento         =   0.0000;
                  $ordencen          =   0.0000;


                  $precio_regular    =   $funcion->funciones->calculo_precio_regular_fecha($item_c,$item,$fechafin);
                  $descuento         =   $funcion->funciones->descuento_reglas_producto_fecha($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,'',$fechafin);
                  $precio_descuento  =   (float)$precio_regular - (float)$descuento;


                  $ordencen         =    $funcion->funciones->calculo_precio_venta($item_c,$item,$fechafin);
                @endphp

	                @if(($index % 2) == 0 ) 
	                    @php  $color = 'tablafila1'; @endphp
	                @else 
	                    @php  $color = 'tablafila2'; @endphp
	                @endif

	                <tr>
	                    <td class='{{$color}}'> {{$item_c->NOM_EMPR}}</td>
	                    <td class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>

                      <td class='negrita {{$color}}'>
                          S/. {{$precio_regular}}
                      </td>
                      <td class='negrita {{$color}}'>
                          S/. {{$descuento}}
                      </td>
                      <td class='negrita {{$color}}'>
                          S/. {{$precio_descuento}}
                      </td>  

                      <td cclass='negrita {{$color}}'>
                        @if($ordencen > 0) 
                          S/. {{$ordencen}} 
                        @else
                          - 
                        @endif       
                      </td>

	                </tr>
 
	            @endforeach
	        @endforeach

          </table>
        </article>
    </section>
</body>
</html>