
<table id="table_group" class="table table-hover table-fw-widget">
  <thead>
    <tr>
      <th>Movil</th>
      <th>Fechas</th>
      <th>Codigo</th>
      <th>Cliente</th>
      <th>Producto</th>
      <th>Muestra</th>
      <th>Cantidad</th>
      <th>Kilos</th>
      <th>Sacos</th>
      <th>Palet</th>

    </tr>
  </thead>
  <tbody>
    @php $grupo         =   ""; @endphp
    @php $grupo_movil   =   ""; @endphp
    @php $conteo_mobil  =   0; @endphp
    @php $grupo_movil_c =   0; @endphp


    @foreach($listaordendespacho as $index => $item)

      @php
        //agrupar por grupo movil
        $array_respuesta   =   $funcion->funciones->crearrolwpan($item->grupo_movil,$index,$grupo_movil);
        $sw_crear_movil    =   $array_respuesta['sw_crear'];
        $grupo_movil       =   $array_respuesta['grupo'];
      @endphp

      <tr>

          @if((int)$item->grupo_movil > 0 or $grupo_movil_c = $item->grupo_movil)
            @php 
              $conteo_mobil      =   $conteo_mobil + 1;
              $grupo_movil_c     =   $item->grupo_movil;
            @endphp
          @else
            @if($item->grupo_movil == '0') 
                @php $conteo_mobil      =   0; @endphp
            @endif
          @endif

          <td>
            <b>{{$item->grupo_movil}}</b>
          </td>
          <td class="cell-detail">
            <span><b>Pedido</b> : {{date_format(date_create($item->fecha_pedido), 'd-m-Y')}} </span> 
            <span><b>Entrega</b> : {{date_format(date_create($item->fecha_entrega), 'd-m-Y')}} </span>
          </td>
          <td><b>PEDIDO : </b>{{$item->ordendespacho->codigo}}</td>
          <td class="cell-detail">
            <span><b>Cliente</b> : 
              @if(trim($item->cliente_id) != '')
                {{$funcion->funciones->data_cliente_cliente_id($item->cliente_id)->NOM_EMPR}}
              @endif
            </span> 
            <span><b>Orden Cen</b> : {{$item->orden_cen}}</span>
          </td>
          <td>{{$item->producto->NOM_PRODUCTO}}</td>
          <td>{{number_format($item->muestra, 2, '.', ',')}}</td>
          <td>{{number_format($item->cantidad, 2, '.', ',')}}</td>
          <td>{{number_format($item->kilos, 4, '.', ',')}}</td>
          <td>{{number_format($item->cantidad_sacos, 4, '.', ',')}}</td>
          <td>{{number_format($item->palets, 4, '.', ',')}}</td>
      </tr>


      @if($conteo_mobil == $item->grupo_orden_movil and $item->grupo_orden_movil > 0) 
      <tr>
          <td class='despacho_totales'></td>
          <td class='despacho_totales'></td>
          <td class='despacho_totales'><b>PEDIDO : </b>{{$item->ordendespacho->codigo}}</td>
          <td class='despacho_totales'></td>          
          <td class='despacho_totales'></td>
          <td class='despacho_totales'></td>
          <td class='despacho_totales'></td>
          <td class='despacho_totales'>
            {{number_format($funcion->funciones->totales_kilos_palets_tabla($item->ordendespacho_id,$item->grupo_movil,'kilos'),4,'.',',')}}
          </td>
          <td class='despacho_totales'></td>
          <td class='despacho_totales'>
            {{number_format($funcion->funciones->totales_kilos_palets_tabla($item->ordendespacho_id,$item->grupo_movil,'palets'),4,'.',',')}}
          </td>
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
    });
  </script> 
@endif