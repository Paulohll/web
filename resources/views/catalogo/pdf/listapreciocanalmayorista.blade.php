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
                <th class='titulotabla center tabladp'>DATOS</th>     
                <th colspan="3" class='titulotabla center tablaho'>PRECIO</th>
                <th class='titulotabla center tablaho' >PROMOCION</th>   
            </tr>

            <tr>
                <th width="75" class= 'tabladp'>PRODUCTO</th>
                <th width="10" class= 'titulotabla tablaho'>MPSA</th> 
                <th width="10" class= 'titulotabla tablaho'>OML</th>
                <th width="10" class= 'titulotabla tablaho'>DIST</th> 
                <th width="75" class= 'center tablaho'>REGLAS</th>
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

                      <td width="75" class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>

                      <td width="10" class='{{$color}}'>
                            S/. {{$precio_regular_mpsa}}
                      </td>

                      <td width="10" class='negrita {{$color}}'>
                            S/. {{$precio_regular_oml}}
                      </td>

                      <td width="10" class='negrita {{$color}}'>
                            S/. {{$precio_regular_dist}}

                      </td>
                      <td width="75" class='negrita {{$color}}'>
                            {{$reglas}}
                      </td>



                  </tr>
            @endforeach   


          </table>
        </article>
    </section>
</body>
</html>