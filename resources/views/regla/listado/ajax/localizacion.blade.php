	<!-- ETIQUETA AFECTACION DE DEPARTAMENTOS -->
@if ($item->regla->tiporegla <> 'PRD' && trim($item->regla->departamento_id) <> '')
      <span class="label label-danger label-etiqueta-izquierda"
            data-toggle="tooltip" 
            data-placement="top" 
            title="{{$funcion->funciones->departamento($item->regla->departamento_id)->NOM_CATEGORIA}}">
        <span class="mdi mdi-pin"></span>
      </span>
@endif