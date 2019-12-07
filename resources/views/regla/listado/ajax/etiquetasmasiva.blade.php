@if ($sw == 1)

	@php $item     =   $funcion->funciones->data_regla_producto_cliente($regla_producto_clinete_id); @endphp 

	<div class='etiquetas-reglas'>
        	<div class='etiquetas-reglas-modal'>
			  	<span class="label label-{{$color}} ">
			  		{{$item->regla->codigo}}
			  	</span>
        		@include('regla.listado.ajax.departamento')
			  	@include('regla.listado.ajax.precioregular')
				@include('regla.listado.ajax.descuento')		  			  	
				@include('regla.listado.ajax.localizacion')
        			
        	</div>

	</div>
@endif




