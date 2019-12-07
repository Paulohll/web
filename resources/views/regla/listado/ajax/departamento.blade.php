	<!-- ETIQUETA AFECTACION DE DEPARTAMENTOS -->
@if ($item->regla->tiporegla == 'PRD')
  	<span 	class="label label-primary label-etiqueta-izquierda"
            data-toggle="tooltip" 
            data-placement="top" 
            title="precio regular del departamento {{$funcion->funciones->departamento($item->regla->departamento_id)->NOM_CATEGORIA}}"
    >
      {{$funcion->funciones->departamento($item->regla->departamento_id)->NOM_CATEGORIA}}
  	</span>
@endif