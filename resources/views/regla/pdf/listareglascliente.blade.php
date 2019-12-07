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
		<h2 class="center">HIPERMERCADOS TOTTUS</h2>
        <p class="subtitulo">
          <strong class='fecha'>FECHA : {{date_format(date_create($fechaactual), 'd-m-Y')}}</strong>
        </p>        
      </div>
    </header>
    <section>
        <article>
          <table>
            <tr>
                <th colspan="2" class='titulotabla center tabladp'>DATOS</th>     
                <th colspan="2" class='titulotabla center tablaho'>REGLAS</th>    
            </tr>

            <tr>
                <th width="160" class= 'tabladp'>CLIENTE</th>
                <th width="160" class= 'tabladp'>PRODUCTO</th>
                <th class= 'titulotabla tablaho'>PRECIO PRODUCTO</th>
                <th class= 'titulotabla tablaho'>NOTA DE CREDITO</th>     
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
        </article>
    </section>
</body>
</html>