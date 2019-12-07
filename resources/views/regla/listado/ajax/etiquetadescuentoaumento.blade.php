	<!-- ETIQUETA PARA AUMENTO Y DESCUENTO -->
@if ($item->regla->descuentoaumento == 'AU')
  	<span 	class="label label-{{$color}} label-etiqueta-izquierda"
  	        data-toggle="tooltip" 
            data-placement="top" 
            title="AUMENTO">
  		<span class="mdi mdi-format-valign-top"></span>
  	</span>
	@else
	@if ($item->regla->descuentoaumento == 'DS')
	  	<span class="label label-{{$color}} label-etiqueta-izquierda"
  	        data-toggle="tooltip" 
            data-placement="top" 
            title="DESCUENTO">
	  		<span class="mdi mdi-format-valign-bottom"></span>
	  	</span>
	@endif
@endif