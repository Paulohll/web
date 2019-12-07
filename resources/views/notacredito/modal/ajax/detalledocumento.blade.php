<div class="modal-header">
	<div class="panel-heading">
		{{$documento->TXT_EMPR_RECEPTOR}} , {{$documento->NRO_SERIE}}-{{$documento->NRO_DOC}}
		<span class="panel-subtitle"><b>{{date_format(date_create($documento->FEC_EMISION), 'd-m-Y')}}</b></span>
	</div>
</div>
<div class="modal-body">

  <div class="scroll_text">
    <table class="table">
      <thead>
        <tr>
          <th>PRODUCTO</th>
          <th>CANTIDAD</th>
          <th>PRECIO</th>
          <th>TOTAL</th>
          <th class="warning">REGLAS</th>
          <th class="warning">NOTA CREDITO</th>
        </tr>
      </thead>
      <tbody>

      @php
        $sumatotal     = 0.0000;
        $total         = 0.0000;
      @endphp

      @foreach($lista_productos as $index => $item)
          <tr>
            <td>{{$item->TXT_NOMBRE_PRODUCTO}}</td>
            <td>{{number_format($item->CAN_PRODUCTO, 3, '.', ',')}}</td>
            <td>{{number_format($item->CAN_PRECIO_UNIT, 4, '.', ',')}}</td>
            <td>{{number_format($item->CAN_VALOR_VTA, 2, '.', ',')}}</td>
            <td>
              @php $reglas    =   $notacredito->descripcion_reglas_monto($documento_id,$referencia_id,$item->COD_PRODUCTO,$ordencen_id,$reglas_id); @endphp

              @if(count($reglas) > 0 )              
                {{implode(' | ', $reglas)}}
              @else

                <span class="badge badge-primary btn-eyes btn-detalle-regla-agregar"
                      id="regla"

                      data_oredencen="{{$ordencen_id}}"
                      data-producto="{{$item->COD_PRODUCTO}}"
                      data-contrato="{{$contrato_id}}"
                      data_reglas="{{$txt_reglas_id}}"
                      data_documento='{{$documento_id}}'
                      data_referencia='{{$referencia_id}}'
                      >
                  <span class="mdi mdi-rotate-left"></span>
                </span>

              @endif
            </td>

            @php
              $total          =  $notacredito->monto_descuento_nc_documentocontable_producto($documento_id,$referencia_id,$item->COD_PRODUCTO,$ordencen_id,$reglas_id);
              $sumatotal      =  $sumatotal + $total;
            @endphp

            <td class='center'>{{number_format($total, 4, '.', ',')}}</td>

          </tr>   
      @endforeach

      </tbody>
      <thead>
        <tr>
          <th colspan='5' class='right'><b>TOTAL</b></th>
          <th class='center'><b>{{number_format($sumatotal, 4, '.', ',')}}</b></th>
        </tr>
      </thead>

    </table>
  </div>
</div>
