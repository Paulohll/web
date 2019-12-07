<div class="modal-header">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title"><strong>{{$funcion->funciones->data_cliente($cuenta_id)->NOM_EMPR}}</strong></h3>
  <h5 class="modal-title">{{$documento['NRO_SERIE']}} - {{$documento['NRO_DOC']}}</h5>
</div>
<div class="modal-body">

    <table id="tabladetalleproductonc_lista" class="table table-striped table-hover table-fw-widget dt-responsive nowrap listatabla" style='width: 100%;'>
      <thead>

        <tr> 
          <th>Material / Servicio</th>
          <th>Cantidad Real</th>
          <th>Precio</th>
          <th>Total</th>
          <th class='columna-success-table'>Cantidad NC</th>
          <th class='columna-warning-table'>Cantidad Faltante</th>
        </tr>

      </thead>
      <tbody>
        @while ($row = $lista_detalle_producto->fetch())

          @php
            $cantidad_faltante      =   $row['CAN_PRODUCTO'];
            $cantidad_nc_producto   =   $funcion->funciones->data_detalle_producto_sum_cantidad($row['COD_TABLA'],$row['COD_PRODUCTO']);
          @endphp

          <tr class= 'fila_producto'>
            <td class="cell-detail">
              <span>{{$row['TXT_NOMBRE_PRODUCTO']}}</span>
              <span class="cell-detail-description-producto">{{$row['UNIDAD_MEDIDA']}}</span>
            </td>
            <td class= 'columna-cantidad'>{{$row['CAN_PRODUCTO']}}</td>
            <td class= 'columna-precio'>{{$row['CAN_PRECIO_UNIT']}}</td>
            <td class= 'columna-importe'>{{$row['CAN_VALOR_VTA']}}</td>
            <td class= 'columna-cantidad-nc columna-success-table'>
              @if(count($cantidad_nc_producto)>0)
                @php
                  $cantidad_faltante      =   $cantidad_faltante - $cantidad_nc_producto->CAN_PRODUCTO;
                @endphp
                {{number_format($cantidad_nc_producto->CAN_PRODUCTO, 4, '.', ',')}}    
              @endif
            </td>
            <td class= 'ccolumna-cantidad-faltante columna-warning-table'>
              {{number_format($cantidad_faltante, 4, '.', ',')}}
            </td>
          </tr>
        @endwhile
      </tbody>
      <tfooter>
        <tr>
        <th colspan="3"></th>
          <th class='rigth'>{{$documento['CAN_TOTAL']}}</th>
          <th></th>
          <th></th>
        </tr>
      </tfooter>
    </table>


</div>
<div class="modal-footer">
  <button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
</div>

