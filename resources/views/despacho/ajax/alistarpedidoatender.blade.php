
<table id="tableprecios" class="table table-striped table-hover table-fw-widget">
  <thead>
    <tr>
      <th>Empresa Solicitante</th>
      <th>Centro Solicitante</th>
      <th>Usuario Solicitante</th>
      <th>Codigo</th>
      <th>Fecha Pedido</th>
      <th>Estado</th>
      <th>Opción</th>
    </tr>
  </thead>
  <tbody>

    @foreach($listaordenatender as $item)
      <tr>

        <td class="cell-detail"> 
          <span>{{$item->empresa->NOM_EMPR}}</span>
        </td>
        <td class="cell-detail"> 
          <span>{{$item->centro->NOM_CENTRO}}</span>
        </td>

        <td class="cell-detail"> 
          <span>{{$funcion->funciones->data_usuario($item->usuario_crea)->nombre}}</span>
        </td>

        <td>{{$item->codigo}}</td>
        <td>{{date_format(date_create($item->fecha_crea), 'd-m-Y H:i')}}</td>

        <td>

          @if($item->COD_CATEGORIA == 'EPP0000000000003') 
            @if($funcion->funciones->pedido_producto_registrado_despacho($item) == '0') 
              <span class="badge badge-warning">{{$item->NOM_CATEGORIA}}</span> 
            @else
                <span class="badge badge-success">PARCIALMENTE ATENDIDA</span> 
            @endif
          @else
            @if($item->COD_CATEGORIA == 'EPP0000000000002') 
                <span class="badge badge-primary">{{$item->NOM_CATEGORIA}}</span>
            @else
              @if($item->COD_CATEGORIA == 'EPP0000000000004') 
                  <span class="badge badge-success">{{$item->NOM_CATEGORIA}}</span>
              @else
                  <span class="badge badge-danger">{{$item->NOM_CATEGORIA}}</span>
              @endif
            @endif
          @endif


        </td>

        <td class="rigth">
          <div class="btn-group btn-hspace">
            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
            <ul role="menu" class="dropdown-menu pull-right">
              <li>
                <a href="{{ url('/atender-orden-despacho/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                  Atender Pedido
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