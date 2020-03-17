<table class="table table-pedidos-despachos" style='font-size: 0.85em;' id="tablepedidodespacho" >
  <thead>
    <tr>
      <th>Movil</th>
      <th>Fechas</th>
      <th>Cliente</th>
      <th>Producto</th>
      <th>Muestra</th>
      <th>Cantidad</th>
      <th>Atender</th>


      <th>Kilos</th>
      <th>Sacos</th>
      <th>Palet</th>
      <th>Sel</th>
    </tr>
  </thead>
  <tbody>

  @php $grupo         =   ""; @endphp
  @php $grupo_movil   =   ""; @endphp
  @php $conteo_mobil  =   0; @endphp
  @php $grupo_movil_c =   0; @endphp

  @foreach($ordendespacho->detalleordendespacho as $index => $item)

    @php
      //agrupar por grupo movil
      $array_respuesta   =   $funcion->funciones->crearrolwpan($item->grupo_movil,$index,$grupo_movil);
      $sw_crear_movil    =   $array_respuesta['sw_crear'];
      $grupo_movil       =   $array_respuesta['grupo'];

      $mobil_cero        =   $funcion->funciones->cantidad_mobil_cero($ordendespacho->id);

    @endphp

    <tr
      class='fila_pedido'
      data_detalle_orden_despacho='{{$item->id}}'
    >

        @if((int)$item->grupo_movil > 0 or $grupo_movil_c = $item->grupo_movil)
          @php 
            $conteo_mobil      =   $conteo_mobil + 1;
            $grupo_movil_c     =   $item->grupo_movil;
          @endphp
        @else
          @if($item->grupo_movil == '0' or $grupo_movil_c = $mobil_cero) 
            @php 
              $conteo_mobil      =   $conteo_mobil + 1;
              $grupo_movil_c     =   $mobil_cero;
            @endphp
          @endif
        @endif

        <td class='center'>
          <b>{{$item->grupo_movil}}</b>
        </td>
        <td class="cell-detail">
          <span><b>Pedido</b> : {{date_format(date_create($item->fecha_pedido), 'd-m-Y')}} </span> 
          <span><b>Entrega</b> : {{date_format(date_create($item->fecha_entrega), 'd-m-Y')}} </span>
        </td>
        <td class="cell-detail">
          <span><b>Cliente</b> : 
            @if(trim($item->cliente_id) != '')
              {{$funcion->funciones->data_cliente_cliente_id($item->cliente_id)->NOM_EMPR}}
            @endif
          </span> 
          <span><b>Orden Cen</b> : {{$item->orden_cen}}</span>
        </td>

        <td class="cell-detail">
          <span>{{$item->producto->NOM_PRODUCTO}}</span>
          <span class="cell-detail-description-producto">
          {{$funcion->funciones->data_categoria($item->producto->COD_CATEGORIA_UNIDAD_MEDIDA)->NOM_CATEGORIA}} de  {{$item->producto->CAN_PESO_SACO}} kg
          </span>
        </td>

        <td>{{number_format($item->muestra, 2, '.', ',')}}</td>
        <td>{{number_format($item->cantidad, 2, '.', ',')}}</td>

        <td>

            <input type="text"
             id="{{$item->id}}{{$ordendespacho->codigo}}" 
             name="catidad_atender"
             value="{{number_format($item['cantidad_atender'], 2, '.', ',')}}"
             class="form-control input-sm dinero updatepriceatender"
            >
          
        </td>

        <td>{{number_format($item->kilos, 4, '.', ',')}}</td>
        <td>{{number_format($item->cantidad_sacos, 4, '.', ',')}}</td>
        <td>{{number_format($item->palets, 4, '.', ',')}}</td>

        <td>

          <div class="text-center be-checkbox be-checkbox-sm has-primary">
            <input  
              type="checkbox"
              class="{{$item->id}} input_asignar_lp"
              id="{{$item->id}}">
            <label  for="{{$item->id}}"
                  data-atr = "ver"
                  class = "checkbox checkbox_asignar_lp"                    
                  name="{{$item->id}}"
            ></label>
          </div>

        </td>


    </tr>


    @if(
        ($conteo_mobil == $mobil_cero  and $item->grupo_orden_movil == 0)
        or
        ($conteo_mobil == $item->grupo_orden_movil and $item->grupo_orden_movil >= 0)
       ) 
    <tr>
        <td class='despacho_totales'></td>
        <td class='despacho_totales'></td>
        <td class='despacho_totales'></td>          
        <td class='despacho_totales'></td>
        <td class='despacho_totales'></td>
        <td class='despacho_totales'></td>
        <td class='despacho_totales'></td>
        <td class='despacho_totales'>
          {{number_format($funcion->funciones->totales_kilos_palets_tabla($item->ordendespacho_id,$item->grupo_movil,'kilos'),4,'.',',')}}
        </td>
        <td class='despacho_totales'>
          {{number_format($funcion->funciones->totales_kilos_palets_tabla($item->ordendespacho_id,$item->grupo_movil,'cantidad_sacos'),4,'.',',')}}
        </td>
        <td class='despacho_totales'>
          {{number_format($funcion->funciones->totales_kilos_palets_tabla($item->ordendespacho_id,$item->grupo_movil,'palets'),4,'.',',')}}
        </td>
        <td class='despacho_totales'></td>
        <td class='despacho_totales'></td>
    </tr>
      @php $grupo_movil_c =   0; @endphp
      @php $conteo_mobil  =   0; @endphp
    @endif
 

  @endforeach
  </tbody>
</table>  

@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
       App.dataTables();

      $('.dinero').inputmask({ 'alias': 'numeric', 
      'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 
      'digitsOptional': false, 
      'prefix': '', 
      'placeholder': '0'});
      
    });
  </script> 
@endif