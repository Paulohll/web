<!DOCTYPE html>

<html lang="es">

<head>
	<title>Pedido ({{$pedido->codigo}}) </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}"> 
	<style>
		.izquierda{
			text-align: right;
		}

		.menu{
		    overflow:hidden;
		    width 	: 730px;
		    display : table;
		    /*border 	: 1px solid black;*/
		}

		.menu .left{
		    width	: 	50%
		    float	:	left;
		    display : 	table-cell; 
		    text-align: center;     
		}


		.menu .right{
		    width	: 	50%
		    float	:	left;
		    border  :	1px solid black; 
		    display : 	table-cell; 
		    text-align: center; 
		    border-radius: 4px ;    
		}

		.menu .left h1{
			font-size:  1.2em;
			/*border   :  1px solid red;*/
		}
		.menu .left h3{
			font-size:  0.8em;
			font-weight: normal;
			/*border   :  1px solid red;*/
		}
		.menu .left h4{
			font-size:  0.8em;
			font-weight: normal;	
			/*border: 1px solid blue;*/
		}

		.top .det1{
			width: 718px;
			font-size: 0.8em;
			margin-top: 5px;
			border: 1px solid #000;
			border-radius: 4px;
			padding: 5px;

		}
		.top .det1 p{
			margin-top: 1px;
			margin-bottom: 3px;
		}

		.det2{
			margin-top: 5px;
		    overflow:hidden;
		    width 	: 730px;
		    display : table;
			border: 1px solid #000;
			border-radius: 4px;
		    font-size: 0.8em;
		    padding: 5px;
		}

		.det2 .d1,.det2 .d2,.det2 .d3{
		    width	: 	50%
		    float	:	left;
		    display : 	table-cell;     
		}

		table {
		    border-collapse: collapse;
		    width 	: 730px;
			margin-top: 15px;
		    font-size: 0.7em;    
		}

		th, td {
		    padding: 8px;
		    text-align: left;
		    border-bottom: 1px solid #ddd;
		}

		.titulo{
			text-align: center;
			font-size: 0.95em;
		}
		.codigo{
			width: 50px;
		}
		.descripcion{
			width: 250px;
		}
		.unidad{
			width: 40px;
		}
		.cantidad{
			width: 40px;
		}
		.precio{
			width: 80px;
		}
		.importe{
			width: 100px;
		}


		.totales{
			margin-top: 10px;
		    overflow:hidden;
		    width 	: 730px;
		    display : table;
		    /*border 	: 1px solid black;*/
		}

		.totales .left{
		    width	: 	65%
		    float	:	left;
		    display : 	table-cell;  
		   	/*border      : 1px solid red;  */ 
		}


		.totales .right{
		    width	: 	35%
		    float	:	left;
		    /*border  :	1px solid black; */
		    display : 	table-cell; 
		      
		}

		.totales .right p{
			font-size 	: 0.75em;
			margin-top	: 0px;
			margin-bottom 	: 1px;	

		}

		.totales .right .descripcion{
			display 	: inline-block;
			width 		: 55%;

		}
		.totales .right .monto{
			display 	: inline-block;
			width 		: 40%;

		}

		.totales .left .uno{
		    display     : inline-block;
		    width       : 25%;
		}
		.totales .left .dos{
		    /*border: 1px solid blue;   */ 
		    display     : inline-block;
		    width       : 70%;
		    font-size   : 0.75em;

		}
		.totales .left .dos p{
		    margin-top: 5px;
		    margin-bottom: 5px;
		}
		.totales .left .derecha{
		    margin-top: 55px;
		}
		.totales .left .uno img{
		    /*border: 1px solid red;*/
		    width: 100px;
		    position: absolute;
		    top: -87px;

		}
		footer .observacion{
		    border-top: 1px solid #000;
		    border-bottom:  1px solid #000;
		}
		footer .observacion h3 {
		    /*border: 1px solid red;*/
		    margin-top: 2px;
		    margin-bottom: 2px;
		    font-size: 0.9em;
		}
		footer .observacion p {
		    /*border: 1px solid red;*/
		    margin-top: 0px;
		    margin-bottom: 2px;    
		    font-size: 0.8em;
		}

		.badge-warning {
		    background-color: #f6c163;
		}

		.badge-danger {
		    background-color: #eb6357;
		}

		.badge-default {
		    background-color: #d9d9d9;
		}

		.badge-success{
			background-color: #37b358;
		}


	</style>


</head>

<body>
    <header>
	<div class="menu">
	    <div class="left">
	    		<h1>{{$funcion->funciones->data_empresa($pedido->empresa_id)->NOM_EMPR}}</h1>
	    		<h3>
	    			{{$funcion->funciones->data_direccion_empresa($pedido->empresa_id)->NOM_DIRECCION}}
	    		</h3>
	    </div>
	    <div class="right">
	    		<h3>R.U.C. {{$funcion->funciones->data_empresa($pedido->empresa_id)->NRO_DOCUMENTO}}</h3> 
	    		<h3>{{$titulo}}</h3>
	    		<h3>{{$pedido->codigo}}</h3> 
	    </div>
	</div>
    </header>


    <section>
        <article>

			<div class="top">

			    <div class="det1">
	   				<p>
	   					<strong>Señor (es) :</strong> {{$funcion->funciones->data_cliente($pedido->cuenta_id)->NOM_EMPR}}
	   				</p>  		    	
	   				<p>
	   					<strong>RUC :</strong> {{$funcion->funciones->data_cliente($pedido->cuenta_id)->NRO_DOCUMENTO}}
	   				</p>
	   				<p>
	   					<strong>Dirección :</strong> {{$funcion->funciones->data_direccion($pedido->direccion_entrega_id)->NOM_DIRECCION}}
	   				</p>

			    </div>

			    <div class="det1">

	   				<p>
	   					<strong>Fecha de Venta :</strong> {{date_format(date_create($pedido->fecha_venta), 'd/m/Y')}}
	   				</p>  		    	
	   				<p >
	   					<strong>Tipo de Pago :</strong> {{$funcion->funciones->data_categoria($pedido->tipopago_id)->NOM_CATEGORIA}}
	   				</p>
	   				<p >
	   					<strong>Glosa  :</strong> {{$pedido->glosa}}
	   				</p>
	   				<p >
	   					<strong>Vendedor  :</strong> {{$funcion->funciones->data_usuario($pedido->usuario_crea)->nombre}}
	   				</p>
			    </div>
			</div>
        </article>


        <article>

		  <table>
		    <tr>
		      <th class='titulo codigo'>CANTIDAD</th>
		      <th class='titulo descripcion'>PRODUCTO</th>
		      <th class='titulo unidad'>ESTADO</th>
		      <th class='titulo importe'>EMPRESA RECEPCION</th>
		      <th class='titulo cantidad'>PRECIO</th>
		      <th class='titulo precio'>IMPORTE</th>

		    </tr>

		    @foreach($pedido->detallepedido as $item)

	            @php $color   =   ''; @endphp

	            @if($item->estado_id == 'EPP0000000000003')
	              	@php $color   =   'badge-warning'; @endphp
	            @else
		            @if($item->estado_id == 'EPP0000000000004') 
		              	@php $color   =   'badge-success'; @endphp 
		            @else
			            @if($item->estado_id == 'EPP0000000000005') 
			              	@php $color   =   'badge-danger'; @endphp 
			            @else
			                @php $color   =   'badge-default'; @endphp
			            @endif
		            @endif
	            @endif

			    <tr class='{{$color}}'>
				    <td class='titulo'>{{$item->cantidad}}</td>
				    <td>{{$item->producto->NOM_PRODUCTO}}
				    	<br>
				    	ORDEN : {{$item->orden_id}}
				    </td>

				    <td class='titulo'>

			            @if(is_null($item->estado_id) or $item->estado_id == '')
			              	GENERADO
			            @else
			                {{$funcion->funciones->data_categoria($item->estado_id)->NOM_CATEGORIA}}
			            @endif

						
					</td>
				    <td class='titulo'>
			            @if(is_null($item->empresa_receptora_id) or $item->empresa_receptora_id == '')
			              	{{$funcion->funciones->data_empresa($item->empresa_id)->NOM_EMPR}}
			            @else
			                {{$funcion->funciones->data_empresa($item->empresa_receptora_id)->NOM_EMPR}}
			            @endif
				    </td>
				    <td class='titulo'>{{number_format($item->precio,4,'.','')}}</td>
				    <td class='titulo'>{{number_format($item->total,2,'.','')}}</td>
			    </tr>
		    @endforeach		    
		  </table>
        </article>


        <article>


			<div class="totales">

			    <div class="left">

			    </div>

			    <div class="right">
			    		<p class='descripcion izquierda'>
			    			SUB TOTAL 
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format($pedido->subtotal,2,'.',',')}}
			    		</p>

			    		<br>
			    		<p class='descripcion izquierda'>
			    			OP. GRAVADA
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format($pedido->subtotal,2,'.',',')}}
			    		</p>


			    		<br>
			    		<p class='descripcion izquierda'>
			    			I.G.V. 18%
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format($pedido->igv,2,'.',',')}}
			    		</p>

			    		<br>
			    		<p class='descripcion izquierda'>
			    			IMPORTE TOTAL
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format($pedido->total,2,'.',',')}}
			    		</p>


			    </div>

			</div>

        </article>



    </section>


</body>
</html>