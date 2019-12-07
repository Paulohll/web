	<!-- ETIQUETA AFECTACION DE DEPARTAMENTOS -->
@if ($item->regla->tiporegla <> 'PRD')
  	<span 	class="label label-{{$color}} label-etiqueta-izquierda"
            data-toggle="tooltip" 
            data-placement="top" 
            title="descuento para la regla"
    >
	    @if($item->regla->tipodescuento == 'POR') 
	      %
	    @else 
	      S/.
	    @endif
      	{{number_format($item->regla->descuento, 4, '.', ',')}}
  	</span>
@endif