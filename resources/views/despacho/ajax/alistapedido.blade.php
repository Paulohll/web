

<input type="hidden" name="grupo" id='grupo' value='{{$grupo}}'>
<input type="hidden" name="correlativo" id='correlativo' value='{{$correlativo}}'>


<div class="main-content container-fluid" style = "padding: 0px;">
  <div class="row">
    <div class="col-sm-12" style = "padding-left: 2px;padding-right : 2px">
      <div class="panel panel-default panel-table">
        <div class="panel-heading"><b>Solicitud de pedido</b>

          <div class="tools dropdown show">
            <div class="dropdown">
              <span class="icon mdi mdi-more-vert dropdown-toggle" id="menudespacho" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></span>

              <ul class="dropdown-menu" aria-labelledby="menudespacho" style="margin: 7px -169px 0px;">

                <li><a href="#" class='crearmobil'>Crear mobil</a></li>
                <li><a href="#" class='cambiarfechaentrega'>Modificar fecha de entrega</a></li>

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


                <th>Muestra</th>
                <th>Cantidad</th>
                <th>Kilos</th>
                <th>Sacos</th>
                <th>Palet</th>
                <th>Totales</th>
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
                      data_producto="{{$item['producto_id']}}"
                    >
                    <td class='center'>
                      <span class="badge badge-danger cursor eliminar-producto-despacho">
                        <span class="mdi mdi-close" style='color: #fff;'></span>
                      </span>
                    </td>


                    @if($sw_crear_movil == 1 and $item['grupo_movil'] <> '0') 
                      <td rowspan = "{{$item['grupo_orden_movil']}}" class='center fondogris'>
                        <b>{{$item['grupo_movil']}}</b>
                      </td>
                    @else
                      @if($item['grupo_movil'] == '0') 
                        <td class='center'>
                          <b>{{$item['grupo_movil']}}</b>
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
                    <td class="cell-detail relative" rowspan = "{{$item['grupo_orden']}}" > 
                      <span><b>Cliente</b> : {{$item['empresa_cliente_nombre']}}</span>
                      <span><b>Orden Cen</b> : {{$item['orden_cen']}}</span>


                      @if($item['tipo_grupo_oc'] == 'oc_grupo') 
                        <div class="text-center be-checkbox be-checkbox-sm has-primary absolute" style="bottom: 10px;right: 6px;" >
                          
                          <input  
                            type="checkbox"
                            class="{{$item['grupo']}}{{$item['orden_cen']}} input_asignar_gop"
                            id="{{$item['grupo']}}{{$item['orden_cen']}}" 
                            data_check_oc="{{$item['grupo']}}{{$item['orden_cen']}}">

                          <label  for="{{$item['grupo']}}{{$item['orden_cen']}}"
                                data-atr = "ver"
                                class = "checkbox checkbox_asignar_gop"                    
                                name="{{$item['grupo']}}{{$item['orden_cen']}}"
                          ></label>

                        </div>
                      @endif


                    </td> 
                    @endif

                    <td class="cell-detail relative">
                      <span>{{$item['nombre_producto']}}</span>
                      <span class="cell-detail-description-producto">
                      {{$item['nombre_unidad_medida']}} de  {{$item['presentacion_producto']}} kg
                      </span>
                      <i class="mdi mdi-settings configuracion-despacho-cantidad"></i>
                    </td>

                    <td>

                        <input type="text"
                         id="muestra" 
                         name="muestra"
                         value="{{number_format($item['muestra'], 2, '.', ',')}}"
                         class="form-control input-sm dinero dineromuestra updatepricemuestrad"
                        >
                    </td>



                    <td>

                        <input type="text"
                         id="precio" 
                         name="precio"
                         value="{{number_format($item['cantidad'], 2, '.', ',')}}"
                         class="form-control input-sm dinero updatepriced"
                        >
                      

                    </td>
                    <td class='center'>{{number_format($item['kilos'],4,'.',',')}}</td>
                    <td class='center'>{{number_format($item['cantidad_sacos'],4,'.',',')}}</td>
                    <td class='center'>{{number_format($item['palets'],4,'.',',')}}</td>

                    @if($sw_crear_movil == 1 and $item['grupo_movil'] <> '0') 
                      <td rowspan = "{{$item['grupo_orden_movil']}}" class='fondogris cell-detail'>
                          <span><b>Kilos</b> : 
                            {{number_format($funcion->funciones->totales_kilos_palets($array_detalle_producto,$item['grupo_movil'],'kilos'),4,'.',',')}}
                          </span>
                          <span><b>Palets</b> :
                            {{number_format($funcion->funciones->totales_kilos_palets($array_detalle_producto,$item['grupo_movil'],'palets'),4,'.',',')}} 
                          </span>
                      </td>
                    @else
                      @if($item['grupo_movil'] == '0') 
                        <td class='cell-detail'>
                          <span><b>Kilos</b> : {{number_format($item['kilos'],4,'.',',')}}</span>
                          <span><b>Palets</b> : {{number_format($item['palets'],4,'.',',')}}</span>
                        </td>
                      @endif
                    @endif

                    <td>

                      <div class="text-center be-checkbox be-checkbox-sm has-primary">
                        <input  
                          type="checkbox"
                          class="{{$item['correlativo']}} input_asignar_lp"
                          id="{{$item['correlativo']}}"
                          data_check_sel="{{$item['grupo']}}{{$item['orden_cen']}}"

                          @if($item["tipo_grupo_oc"] == "oc_grupo") disabled @endif>
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
    <br>
    <div class="col-xs-12" style="text-align: right;">

      <form method="POST"  action="{{ url('/crear-orden-pedido-despacho/'.$opcion_id) }}" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          {{ csrf_field() }}
        <input type="hidden" name="array_detalle_producto" id='array_detalle_producto' value='{{json_encode($array_detalle_producto)}}'>
        <button type="submit" class="btn btn-space btn-primary">Guardar</button>
      </form>

    </div>

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.dinero').inputmask({ 'alias': 'numeric', 
    'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 
    'digitsOptional': false, 
    'prefix': '', 
    'placeholder': '0'});
  });
</script> 

