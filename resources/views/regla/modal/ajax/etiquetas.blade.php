@foreach($listareglas as $item)

  	@if($item->activo == 1)  
		<div class='etiquetas-reglas-modal'>

		  	<span class="label label-{{$color}} po-detalle-modal{{$item->id}}"
			  		  data_id='{{$item->id}}'
			  		  data_sw='1'
			  		  data_regla='{{$item->regla_id}}'
			  		  data-toggle='popovers'>{{strtoupper(substr($item->regla->nombre, 0, 8))}} ... </span>

			@include('regla.listado.ajax.departamento')

			@include('regla.listado.ajax.precioregular')
			@include('regla.listado.ajax.descuento')

			@include('regla.listado.ajax.localizacion')	
		  	<span class="label label-success label-etiqueta-eliminar"
		  		data_id='{{$item->id}}'
		  		data_regla='{{$item->regla_id}}'
		  		><span class="mdi mdi-delete"></span></span>


		</div>
  	@else 
		<div class='etiquetas-reglas-modal'>

		  <span class="label label-default po-detalle-modal{{$item->id}}"
			  		  data_id='{{$item->id}}'
			  		  data_sw='1'
			  		  data_regla='{{$item->regla_id}}'
			  		  data-toggle='popovers'>{{$item->regla->nombre}}</span>

		</div>
  	@endif

@endforeach