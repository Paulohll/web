
<input type="hidden" name="array_detalle_producto" id='array_detalle_producto' value='{{json_encode($array_detalle_producto)}}'>
<div class="main-content container-fluid" style = "padding: 0px;">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default panel-table">
        <div class="panel-heading"><b>Solicitud de pedido</b>
          <div class="tools">
          </div>
        </div>
        <div class="panel-body">
          <table class="table table-hover" style='font-size: 0.85em;'>
            <thead>
              <tr>
                <th>Movil</th>
                <th>Fechas</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad (bls)</th>
                <th>Palets</th>
                <th>Cantidad (sacos)</th>
                <th>Palets / Kilos</th>
              </tr>
            </thead>
            <tbody>
              @php $grupo   =   ""; @endphp
              @foreach($array_detalle_producto as $index => $item)

                  @php 
                    $array_respuesta   =   $funcion->funciones->crearrolwpan($item['grupo'],$index,$grupo);
                    $sw_crear          =   $array_respuesta['sw_crear'];
                    $grupo             =   $array_respuesta['grupo'];
                  @endphp

                  <tr>

                    <td>{{$index + 1}}</td>
                    @if($sw_crear == 1) 
                      <td class="cell-detail" rowspan = "{{$item['grupo_orden']}}">
                        <span><b>Pedido</b> : {{$item['fecha_pedido']}}</span>
                        <span><b>Entrega</b> : {{$item['fecha_entrega']}}</span>
                      </td>
                    @endif
                    @if($sw_crear == 1) 
                    <td class="cell-detail" rowspan = "{{$item['grupo_orden']}}"> 
                      <span><b>Cliente</b> : {{$item['empresa_cliente_nombre']}}</span>
                      <span><b>Orden Cen</b> : {{$item['orden_cen']}}</span>
                    </td> 
                    @endif
                    <td>{{$item['nombre_producto']}} {{$item['grupo']}} {{$item['grupo_orden']}}</td>
                    <td>{{number_format($item['cantidad'], 2, '.', ',')}}</td>
                    <td>{{$item['cantidad']}}</td>
                    <td>{{$item['cantidad']}}</td>

                    <td></td>

                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

