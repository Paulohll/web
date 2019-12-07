<div class='etiquetas-reglas'>

    @php $sw    =   0; @endphp
	@foreach($listareglaproductoclientes as $item)

        @if ($item->producto_id == $producto_id && $item->cliente_id == $cliente_id && $tipo == $item->regla->tiporegla && $item->contrato_id == $contrato_id) 

        	<div class='etiquetas-reglas-modal'>
			  	<span class="label label-{{$color}} label-etiqueta po-detalle{{$item->id}}"
			  		  data_id='{{$item->id}}'
			  		  data_sw='0'
			  		  data_regla='{{$item->regla_id}}'
			  		  data-toggle='popovers'>
			  		{{$item->regla->codigo}}
			  	</span>

			  	@include('regla.listado.ajax.departamento')
			  	@include('regla.listado.ajax.precioregular')
				@include('regla.listado.ajax.descuento')		  			  	

				@include('regla.listado.ajax.localizacion')	        		
        	</div>

	        @if ($tipo == 'POV') 
	            @php $sw    =   1; @endphp
	        @endif

        @endif
	@endforeach

    @if ($sw == 1) 
		<div class="tooltipfr precio-regular-descuento tooltip-precio">
			<span class="badge badge-defaul">
		      <span class="md-trigger icon mdi mdi-money"></span>
		   	</span>
		  	<span class="tooltiptext">Calculando ...</span>
		</div>
    @endif

</div>



