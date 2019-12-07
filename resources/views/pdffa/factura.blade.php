<!DOCTYPE html>

<html lang="es">

<head>
	<title>Factura ({{$doc->serie_correlativo}}) </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}"> 
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/pdf.css') }} "/>


</head>

<body>
    <header>

	<div class="menu">

	    <div class="left">
	    		<h1>{{$razonsocial}}</h1> 
	    		<h3>{{$direccion}} {{$departamento}} - {{$provincia}} - {{$distrito}}</h3>
	    		<h4>Teléfono : {{$telefono}}</h4>    
	    </div>

	    <div class="right">
	    		<h3>R.U.C. {{$ruc}}</h3> 
	    		<h3>{{$titulo}}</h3>
	    		<h3>{{$doc->serie_correlativo}}</h3> 
	    </div>
	</div>


    </header>
    <section>
        <article>

			<div class="top">

			    <div class="det1">
	   				<p>
	   					<strong>Señor (es) :</strong> {{$doc->cliente->nombre}}
	   				</p>  		    	
	   				<p>
	   					<strong>RUC :</strong> {{$doc->cliente->dniruc}}
	   				</p>
	   				<p>
	   					<strong>Dirección :</strong> {{$doc->cliente->direccion1}}
	   				</p>

					@if ($swcd == 1) 
						<p>
	   					<strong>Documento de Referencia :</strong> {{$doc->sunatdocumentoreferencia->serie_correlativo_referencia}}
	   					</p> 	   				   				
		   			@endif	

			    </div>

			    <div class="det2">

	   				<p class="d1">
	   					<strong>Fecha de Emisión :</strong> {{date_format(date_create($doc->fecha_venta), 'd/m/Y')}}
	   				</p>  		    	
	   				<p class="d2">
	   					<strong>Fecha de Vencimiento :</strong> {{date_format(date_create($doc->fecha_vencimiento), 'd/m/Y')}}
	   				</p>
	   				<p class="d3">
	   					<strong>Condición de Pago  :</strong> {{$formapago}}
	   				</p>
	


			    </div>
			</div>
        </article>
        <article>

		  <table>
		    <tr>
		      <th class='titulo codigo'>CODIGO</th>
		      <th class='descripcion'>DESCRIPCIÓN</th>
		      <th class='titulo unidad'>UNIDAD</th>
		      <th class='titulo cantidad'>CANTIDAD</th>
		      <th class='titulo precio'>PRECIO</th>
		      <th class='titulo importe'>IMPORTE</th>
		    </tr>


		    @foreach($doc->detalledocumento as $item)
			    <tr>
			      <td class='titulo'>{{$item->linea}}</td>
			      <td>{{$item->nombre_producto}}</td>
			      <td class='titulo'>{{$item->unidad_producto}}</td>
			      <td class='titulo'>{{number_format(round($item->cantidad,2),2,'.','')}}</td>
			      <td class='titulo'>{{number_format(round($item->precio_unitario,2),2,'.','')}}</td>
			      <td class='izquierda'>{{number_format(round($item->subtotal_original,2),2,'.',',')}}</td>
			    </tr>
		    @endforeach		    

		    <tr>
		      <td  colspan="6">SON : {{$letras}}</td>
		    </tr>

		  </table>

        </article>

        <article>


			<div class="totales">

			    <div class="left">

			    	<div class='derecha'>
					    <div class="uno">
							<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($doc->sunatdocumento->qr)) !!} ">   
					    </div>

					    <div class="dos">
					    	<p>{{$autorizado}}</p>
					    	<p>{{$representacion}}</p>
					    </div>
			    	</div>


			    </div>

			    <div class="right">
			    		<p class='descripcion izquierda'>
			    			SUB TOTAL {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($subtotal,2),2,'.',',')}}
			    		</p>
			    		<br>
			    		<p class='descripcion izquierda'>
			    			DSCTO GLOBAL {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($doc->descuento,2),2,'.',',')}}
			    		</p>

			    		<br>
			    		<p class='descripcion izquierda'>
			    			OP. GRAVADA {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($montogravado,2),2,'.',',')}}
			    		</p>


			    		<br>
			    		<p class='descripcion izquierda'>
			    			OP. EXONERADA {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($montoexonerado,2),2,'.',',')}}
			    		</p>			    		

			    		<br>
			    		<p class='descripcion izquierda'>
			    			OP. INAFECTA {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($montoinafecto,2),2,'.',',')}}
			    		</p>

			    		<br>	
			    		<p class='descripcion izquierda'>
			    			OP. GRATUITA {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($montobonificacion,2),2,'.',',')}}
			    		</p>

			    		<br>
			    		<p class='descripcion izquierda'>
			    			I.G.V. {{$igv}} {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($doc->igv_original,2),2,'.',',')}}
			    		</p>

			    		<br>
			    		<p class='descripcion izquierda'>
			    			IMPORTE TOTAL  {{$smoneda}}
			    		</p>
			    		<p class='monto izquierda'>
			    			{{number_format(round($importeventa,2),2,'.',',')}}
			    		</p>


			    </div>

			</div>

        </article>

    </section>
    <footer>
        	<div class='observacion'>
        		<h3>OBSERVACIONES SUNAT</h3>
        		<p><strong>Rpta :</strong> {{$doc->sunatdocumento->respuesta}}  <strong>Hash :</strong>  {{$doc->sunatdocumento->hash}} </p>
        	</div>	
    </footer>
</body>
</html>