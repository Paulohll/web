	<!-- ETIQUETA AFECTACION DE DEPARTAMENTOS -->
@if ($item->regla->tiporegla == 'PRD')
  	<span 	class="label label-primary label-etiqueta-izquierda"
            data-toggle="tooltip" 
            data-placement="top" 
            title="nuevo precio regular para {{$funcion->funciones->departamento($item->regla->departamento_id)->NOM_CATEGORIA}}"
    >
      S/. {{number_format($item->regla->descuento, 4, '.', ',')}}
  	</span>
@endif