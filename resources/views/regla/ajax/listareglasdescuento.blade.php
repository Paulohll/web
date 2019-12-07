
<table id="tableprecios" class="table table-striped table-hover table-fw-widget">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Codigo</th>
      <th>Monto</th>
      <th>Utilizada</th>
      <th>Fecha inicio</th>
      <th>Fecha de expiración</th>
      <th>Departamento</th>
      <th>Cantidad Minima</th>
      <th>Aplica</th>
      <th>Estado</th>
      <th>Opción</th>
    </tr>
  </thead>
  <tbody>

    @foreach($listaprecio as $item)
      <tr>

        <td class="cell-detail"> 
          <span>{{strtoupper($item->nombre)}}</span>
          <span class="cell-detail-description"><b>Creada en : </b> {{$item->empresa->NOM_EMPR}}</span>
        </td>

        <td>{{$item->codigo}}</td>

        <td>

          @if($item->tipodescuento == 'POR') 
            %
          @else 
            S/.
          @endif
          {{number_format($item->descuento, 4, '.', ',')}}
        </td>

        <td>
          <span class="badge badge-default">{{$item->cantidadutilizada}}</span>
        </td>
        <td>{{date_format(date_create($item->fechainicio), 'd-m-Y H:i')}}</td>
        <td>
          @if($item->fechafin == $fechavacia) 
            <span class="badge badge-default">ilimitado</span> 
          @else 
            {{date_format(date_create($item->fechafin), 'd-m-Y H:i')}}
          @endif
        </td>

        <td>
          @if(trim($item->departamento_id) == '') 
            <span class="badge badge-default">TODOS</span> 
          @else 
            <span class="badge badge-danger">{{$funcion->funciones->departamento($item->departamento_id)->NOM_CATEGORIA}}</span> 
          @endif
        </td>

        <td>
            <span class="badge badge-default">{{$item->cantidadminima}}</span>
        </td>

        <td> 
          @if($item->documento == 'OV') 
            <span class="badge badge-success">ORDEN VENTA</span>
            @if($item->ind_departamento == 1)
              <span class="badge badge-success">{{$funcion->funciones->departamento($item->departamento_id)->NOM_CATEGORIA}}</span>
            @endif
          @else 
            <span class="badge badge-warning">NOTA CREDITO</span> 
          @endif
        </td>


        <td> 
          @if($item->estado == 'PU') 
            <span class="badge badge-success">PUBLICADO</span>
          @else 
            @if($item->estado == 'NP') 
              <span class="badge badge-warning">NO PUBLICADO</span>
            @else
              <span class="badge badge-danger">CERRADO</span> 
            @endif
          @endif
        </td>
        <td class="rigth">
          
          <div class="btn-group btn-hspace">
            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
            <ul role="menu" class="dropdown-menu pull-right">
              
              <li>
                <a href="{{ url('/gestion-masiva-regla-precio/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                  Gestión masiva
                </a>  
              </li>


            </ul>
          </div>
          
        </td>
      </tr>                    
    @endforeach

  </tbody>
</table>

@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
       App.dataTables();
    });
  </script> 
@endif