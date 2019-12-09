<table id="tablatomapedido" class="table table-striped table-hover table-fw-widget dt-responsive nowrap listatabla" style='width: 100%;'>
  <thead>
    <tr> 

      <th>
        <div class="text-center be-checkbox be-checkbox-sm">
          <input  type="checkbox"
                  class="todo"
                  id="todo"
          >
          <label  for="todo"
                  data-atr = "todas"
                  class = "checkbox"                    
                  name="todo"
            ></label>
        </div>
      </th>

      <th>Codigo</th>
      <th>Fecha Venta</th>
      <th>Cliente</th>
      <th>Documento</th>
      <th>Tipo Pago</th>
      <th>Estado</th>
      <th>Total</th>
      <th>Ver</th>
    </tr>
  </thead>
  <tbody>
   @foreach($listapedidos as $item)
      <tr>

        <td>  

          <div class="text-center be-checkbox be-checkbox-sm">
            <input  type="checkbox"
                    class="{{Hashids::encode(substr($item->id, -8))}}" 
                    id="{{Hashids::encode(substr($item->id, -8))}}" 
                    @if($item->estado != 'EM') disabled @endif>

            <label  for="{{Hashids::encode(substr($item->id, -8))}}"
                  data-atr = "ver"
                  class = "checkbox"                    
                  name="{{Hashids::encode(substr($item->id, -8))}}"
            ></label>
          </div>
        </td>

        <td>{{$item->codigo}}</td>
        <td>{{date_format(date_create($item->fecha_time_venta), 'd-m-Y H:i')}}</td>
        <td class="cell-detail"> 
          <span>{{$item->empresa->NOM_EMPR}}</span>
          <span class="cell-detail-description"><b>Vendedor : </b> {{$funcion->funciones->data_usuario($item->usuario_crea)->nombre}}</span>
          <span class="cell-detail-description"><b>Empresa (registro pedido) : </b> {{$funcion->funciones->data_empresa($item->empresa_id)->NOM_EMPR}}</span>
        </td>
        <td>{{$item->empresa->NRO_DOCUMENTO}}</td>
        <td>{{$funcion->funciones->data_categoria($item->tipopago_id)->NOM_CATEGORIA}}</td>
        <td>
            @if($item->COD_CATEGORIA == 'EPP0000000000003') 

              @if($funcion->funciones->pedido_producto_registrado($item) == '0') 
                <span class="badge badge-warning">{{$item->NOM_CATEGORIA}}</span> 
              @else
                  <span class="badge badge-success">PARCIALMENTE ATENDIDA</span> 
              @endif
              
            @else
                <span class="badge badge-primary">{{$item->NOM_CATEGORIA}}</span>
            @endif

            <br>
            <span class="badge badge-defaul" style='margin-top:5px'>
              <a href="{{ url('/imprimir-pedido/'.Hashids::encode(substr($item->id, -8))) }}" target="_blank">
                Imprimir
              </a>
            </span>
        </td>
        <td
          class='p{{$item->id}}'>
          {{$item->total}}
        </td>
        <td>

          <span class="badge badge-primary btn-eyes btn-detalle-pedido-autorizacion" 
                data-id="{{Hashids::encode(substr($item->id, -8))}}">
            <span class="mdi mdi-eye  md-trigger"></span>
          </span>


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

