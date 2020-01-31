
<input type="hidden" name="array_detalle_producto" id='array_detalle_producto' value='{{json_encode($array_detalle_producto)}}'>
<input type="hidden" name="grupo" id='grupo' value='{{$grupo}}'>
<input type="hidden" name="correlativo" id='correlativo' value='{{$correlativo}}'>


<div class="main-content container-fluid" style = "padding: 0px;">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default panel-table">
        <div class="panel-heading"><b>Solicitud de pedido</b>

          <div class="tools dropdown show">
            <div class="dropdown">
              <span class="icon mdi mdi-more-vert dropdown-toggle" id="menudespacho" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></span>

              <ul class="dropdown-menu" aria-labelledby="menudespacho" style="margin: 7px -169px 0px;">
                <li><a href="#" class='crearmobil'>Crear mobil</a></li> 
              </ul>

            </div>
          </div>





        </div>
        <div class="panel-body">
          <table class="table table-pedidos-despachos" style='font-size: 0.85em;' id="tablepedidodespacho" >
            <thead>
              <tr>
                <th class='center'>X</th>
                <th>Movil</th>
                <th>Fechas</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad (bls)</th>
                <th>Palets</th>
                <th>Cantidad (sacos)</th>
                <th>Palets / Kilos</th>
                <th>Sel</th>
              </tr>
            </thead>
            <tbody>
              @php $grupo         =   ""; @endphp
              @php $grupo_movil   =   ""; @endphp

              @foreach($array_detalle_producto as $index => $item)

                  @php
                    //agrupar por orden cen y producto
                    $array_respuesta   =   $funcion->funciones->crearrolwpan($item['grupo'],$index,$grupo);
                    $sw_crear          =   $array_respuesta['sw_crear'];
                    $grupo             =   $array_respuesta['grupo'];



                    //agrupar por grupo movil
                    $array_respuesta   =   $funcion->funciones->crearrolwpan($item['grupo_movil'],$index,$grupo_movil);
                    $sw_crear_movil    =   $array_respuesta['sw_crear'];
                    $grupo_movil       =   $array_respuesta['grupo'];


                  @endphp

                  <tr class='fila_pedido'
                      data_correlativo="{{$item['correlativo']}}"
                    >
                    <td class='center'>
                      <span class="badge badge-danger cursor eliminar-producto-despacho">
                        <span class="mdi mdi-close" style='color: #fff;'></span>
                      </span>
                    </td>


                    @if($sw_crear_movil == 1 and $item['grupo_movil'] <> '0') 
                      <td rowspan = "{{$item['grupo_orden_movil']}}">
                        {{$item['grupo_movil']}} {{$item['grupo_orden_movil']}}
                      </td>
                    @else
                      @if($item['grupo_movil'] == '0') 
                        <td>
                          {{$item['grupo_movil']}} {{$item['grupo_orden_movil']}}
                        </td>
                      @endif
                    @endif


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
                    <td>{{$item['nombre_producto']}} {{$item['correlativo']}}</td>
                    <td>{{number_format($item['cantidad'], 2, '.', ',')}}</td>
                    <td>{{$item['cantidad']}}</td>
                    <td>{{$item['cantidad']}}</td>


                    @if($sw_crear_movil == 1 and $item['grupo_movil'] <> '0') 
                      <td rowspan = "{{$item['grupo_orden_movil']}}">
                        ssss
                      </td>
                    @else
                      @if($item['grupo_movil'] == '0') 
                        <td>
                          ssss
                        </td>
                      @endif
                    @endif


                    <td>
                      <div class="text-center be-checkbox be-checkbox-sm has-primary">
                        <input  
                          type="checkbox"
                          class="{{$item['correlativo']}} input_asignar_lp"
                          id="{{$item['correlativo']}}" >

                        <label  for="{{$item['correlativo']}}"
                              data-atr = "ver"
                              class = "checkbox checkbox_asignar_lp"                    
                              name="{{$item['correlativo']}}"
                        ></label>
                      </div>

                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

