
<table  class="table table-striped table-hover table-fw-widget dt-responsive nowrap listatabla" style='width: 100%;'>
  <thead>
    <tr> 
      <th>Material / Servicio</th>
      <th>Cantidad</th>
      <th>Precio</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
  @php
      $totales              = 0.0;
  @endphp
  @foreach($lista_detalle_producto as $index => $item)

      @php

          $detalle_producto     = $funcion->funciones->lista_detalle_producto_orden_venta($data_documento_id,$item['producto_id'])->fetch(2);
          $total                = $item['precio']*$item['cantidad'];
          $totales              = $totales + $total;
      @endphp

      <tr>
        <td class="cell-detail">
          <span>{{$detalle_producto['TXT_NOMBRE_PRODUCTO']}}</span>
          <span class="cell-detail-description-producto">{{$data_serie_correlativo}}</span>
        </td>

        <td class='center'>{{$item['cantidad']}}</td>
        <td class='center'>{{number_format($item['precio'], 4, '.', ',')}}</td>
        <td class='right'>{{number_format($total, 4, '.', ',')}}</td>
      </tr>   
  @endforeach
  </tbody>
  <tfooter>
    <tr>
    <th colspan="3"></th>
    <th class=' right'>

      {{number_format($totales, 4, '.', ',')}}
    

    </th>

    </tr>
  </tfooter>

</table>



