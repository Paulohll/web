<div class="main-content container-fluid" style = "padding: 0px;">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default panel-table">
        <div class="panel-heading"><b>Solicitud de pedido</b>
          <div class="tools">

          </div>
        </div>
        <div class="panel-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Fechas</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad (bls)</th>
                <th>Palets</th>
                <th>Cantidad (sacos)</th>
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

                    @if($sw_crear == 1) 
                      <td class="cell-detail" rowspan = "{{$item['rowspan']}}">
                        <span>Pedido : {{$item['fecha_pedido']}}</span>
                        <span>Entrega : {{$item['fecha_entrega']}}</span>
                      </td>
                    @endif

                    @if($sw_crear == 1) 
                    <td class="cell-detail" rowspan = "{{$item['rowspan']}}"> 
                      <span>Cliente : {{$item['empresa_cliente_nombre']}}</span>
                      <span>Orden Cen : {{$item['orden_cen']}}</span>
                    </td> 
                    @endif

                    <td>{{$item['nombre_producto']}} {{$item['grupo']}} {{$item['rowspan']}}</td>
                    <td>{{$item['cantidad']}}</td>
                    <td>{{$item['cantidad']}}</td>
                    <td>{{$item['cantidad']}}</td>



                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

