<!DOCTYPE html>
<html lang="es">

<head>
  <title>{{$titulo}}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}"> 
  <!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/css/pdf.css') }} "/> -->
 <style> 
 .columna_marcada1{
  background:#abc8f7;
  color: #000000;
}

.titulo{
    text-decoration: underline;
    font-style: italic;
}
.subtitulo{
    font-size: 0.7em;
}
.fecha{
    float: right;
}

.titulotabla{
    background: #4285f4;
    color: #fff;
    font-weight: bold;
}
.tabladp{
    background: #bababa;
    color:#fff;
}
.tablaho{
    background: #37b358;
    color:#fff;
}
.tablamar{
    background: #4285f4;
    color:#fff;
}
.tablaagrupado{
    background: #ea4335;
    color:#fff;
}
.reportevacadesc{
        background: #ea4335;
    color: #fff;
    font-weight: bold;
}
.center{
    text-align: center;
}
.fila{
    font-size: 1.2em;
}

.fila p{
    text-align: left;
    color:#999999;
    margin: 1px;
    font-size: 0.85em;
    font-weight: bold;
    font-style: italic;    
}
.fila .punto{
    border-radius: 2px;
    width: 4px;
    height: 4px;
    text-align: center;
    margin: 7px auto;
}
.fila .noasistio{
    background: #ea4335;
}
.fila .asistio{
    background: #28a745;
}

.fila .fecha{
    font-size: 0.85em;
    font-weight: bold;
}
.fila .hora{
    font-size: 0.85em;
    font-weight: bold;        
}

.fila .subtitulo{
    text-align: center;
    color: black;
    font-size: 0.7em;
    font-weight: bold;
    font-style: inherit;
    margin-top: 6px;
    margin-bottom: 6px;
}
.fila .asistencia{
    color: #2d9147; 
    font-weight: bold; 
}

table {
    border-collapse: collapse;
    width   : 730px;
    margin-top: 15px;
    font-size: 0.7em;    
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

/*********** detalle horario ***************/
h2{
    text-align: center;
    font-style: italic;
    text-decoration: underline;
}
.horario{
    border: 1px solid #000;
    padding: 0px 5px 5px 10px;
}
.center{
    text-align: center !important;
}
.negro{
    color: #000 !important;
}

.horario .horainicio{
    padding-left: 125px;
}
.horario .horarefrigeriinicio{
    padding-left: 110px;
}
.horario .horarefrigerifin{
    padding-left: 100px;
}
.horario .horafin{
    padding-left: 131px;
}


.horario .activo{
    color: #37b358 !important;
}
.horario .desactivo{
    color: #eb6357 !important;    
}

.tablafila2{
  background: #e6e3e3;
}
.tablafila1{
  background: #ffffff;
}
 
 
 </style>
</head>
<body>
    <header>
      <div class='reporte'>
        <h3 class="center titulo">{{$empresa}} - {{$centro}}</h3>
        <h5 class="center titulo">{{$listacliente[0]->NOM_EMPR }} </h5>
        <p class="subtitulo">
          <strong class='fecha'>FECHA : {{date_format(date_create($fechaactual), 'd-m-Y')}}</strong>
        </p>


      </div>
    </header>
    <section>
        <article>
          <table>
            <!-- <tr>
                <th colspan="2" class='titulotabla center tabladp'>DATOS</th>     
                <th colspan="4" class='titulotabla center tablaho'>PRECIO</th>    
            </tr> -->

            <tr>
            
                <th width="140" class= 'tabladp'>PRODUCTO</th>

                <th width="10" class= 'titulotabla tablaho'>DEPARTAMENTO</th>              
                <th width="10" class= 'titulotabla tablaho'>PRECIO REGULAR</th> 
                <th width="10" class= 'titulotabla tablaho'>DESCUENTO</th>
                <th width="10" class= 'titulotabla tablaho'>PRECIO TOTAL</th> 
   
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
	                    <!-- <td class='{{$color}}'> {{$item_c->NOM_EMPR}}</td> -->
	                    <td class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>
	                    <td class='negrita {{$color}}'>TODOS</td> 
                      <td class='negrita {{$color}}'>
                          S/. {{$funcion->funciones->calculo_precio_regular($item_c,$item)}}
                      </td>
                      <td class='negrita {{$color}}'>
                          S/. {{$funcion->funciones->descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,'')}}
                      </td>
                      <td class='negrita {{$color}}'>
                          S/. {{$funcion->funciones->precio_descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,'')}}
                      </td>                            
	                </tr>
	                @foreach($lista_precio_regular_departamento as $index_pr => $item_pr)
	                <tr>
	                    <!-- <td class='{{$color}}'> {{$item_c->NOM_EMPR}}</td> -->
	                    <td class='{{$color}}'>{{$item->NOM_PRODUCTO}}</td>
	                    <td class='negrita {{$color}}'>{{$funcion->funciones->departamento($item_pr->departamento_id)->NOM_CATEGORIA}}</td> 
                      <td class='right negrita {{$color}} columna_marcada1'> S/. {{number_format($item_pr->descuento, 2, '.', ',')}}</td>
                      <td class='right negrita {{$color}}'> 
                          S/. {{$funcion->funciones->descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,$item_pr->departamento_id)}}
                      </td>
                      <td class='right negrita {{$color}}'> 
                          S/. {{$funcion->funciones->precio_descuento_reglas_producto($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,$item_pr->departamento_id)}}
                      </td>
	                </tr>     
	                @endforeach  
	            @endforeach
	        @endforeach

          </table>
        </article>
    </section>
</body>
</html>