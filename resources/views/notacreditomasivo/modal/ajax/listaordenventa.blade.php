<table id="tableordenventa" class="table table-striped table-hover table-fw-widget listatabla">
  <thead>
    <tr>
      <th>CodOrden</th>
      <th>Fecha Orden</th>
      <th>Estado</th>
      <th>Proveedor</th>
      <th>Sub Total</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody class='orden_venta_tabla'>
  @foreach($lista_orden as $index => $item)
      <tr class='filaconordenventa'
          data_cod_orden_venta='{{$item->COD_ORDEN}}'
          >
        <td>{{$item->COD_ORDEN}}</td>
        <td>{{$item->FEC_ORDEN}}</td>
        <td>{{$item->TXT_CATEGORIA_ESTADO_ORDEN}}</td>
        <td>{{$item->TXT_EMPR_CLIENTE}}</td>
        <td>{{$item->CAN_SUB_TOTAL}}</td>
        <td>{{$item->CAN_TOTAL}}</td>
      </tr>   
  @endforeach
  </tbody>
</table>

<script type="text/javascript">
      $(document).ready(function(){
        App.dataTables();
        App.formElements();
        $('form').parsley();
      });
</script> 