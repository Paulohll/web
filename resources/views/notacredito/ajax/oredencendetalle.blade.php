
<table id="tablenotacredito" class="table table-striped table-hover table-fw-widget listatabla">
  <thead>
    <tr> 
      <th class= 'tabladp'>FECHA</th>
      <th class= 'tabladp'>TIPO DOCUMENTO</th>
      <th class= 'tabladp'>ORDEN CEN</th>
      <th class= 'tabladp'>N°. FACTURA</th>

      <th class= 'tabladp'>TOTAL RECIBIDO</th>
      <th class= 'tabladp'>ESTADO</th>
      <th class="columna_2"></th>


      <th class= 'warning'>REGLAS</th>
      <th class= 'warning'>NOTA CREDITO</th>
      <th>
        <div class="text-center be-checkbox be-checkbox-sm has-primary">
          <input  type="checkbox"
                  class="todo_asignar input_asignar"
                  id="todo_asignar"
          >
          <label  for="todo_asignar"
                  data-atr = "todas_asignar"
                  class = "checkbox_asignar"                    
                  name="todo_asignar"
            ></label>
        </div>
      </th>

    </tr>
  </thead>
  <tbody>
    @foreach($lista_ordenes as $index => $item)

          @php

            $total_factuta    =   0.0000;
            $nro_fatura       =   '-';
            $estado_fatura    =   '-';
            $cod_documento    =   '';
            $txt_referencia   =   '';
            $factura          =   $notacredito->factura_ordencen($item->COD_ORDEN);
            $total            =   0.0000;

          @endphp

          @if(count($factura)) 
            @php

              $nro_fatura       =   $factura->NRO_SERIE.'-'.$factura->NRO_DOC;
              $total_factuta    =   $factura->CAN_TOTAL;
              $estado_fatura    =   $factura->TXT_CATEGORIA_ESTADO_DOC_CTBLE;
              $cod_documento    =   $factura->COD_DOCUMENTO_CTBLE;
              $txt_referencia   =   $factura->TXT_REFERENCIA;
              $total            =   $notacredito->monto_descuento_nota_credito_factura($cod_documento,$txt_referencia,$regla_id,$item->COD_ORDEN);

            @endphp
          @endif


          <tr class='fila_regla'
              data_contrato='{{$contrato->COD_CONTRATO}}'
              data_documento='{{$cod_documento}}'
              data_oredencen='{{$item->COD_ORDEN}}'
              data_referencia='{{$txt_referencia}}'
              data_tf='{{$total_factuta}}'
              data_tnc='{{$total}}'
              data_reglas='{{implode(",", $regla_id)}}'
              >
            <td>{{date_format(date_create($item->FEC_ORDEN), 'd-m-Y')}}</td>
            <td>ORDEN CEN</td>
            <td>{{$item->NRO_ORDEN_CEN}}</td>
            <td>{{$nro_fatura}}</td>
            <td><b>{{number_format($total_factuta, 4, '.', ',')}}</b></td>
            <td>{{$estado_fatura}}</td>
            <td>
            @if(count($factura))              
                <span class="badge badge-primary badgenotacredito">
                    <span class="mdi mdi-eye"></span>
                </span>
            @endif
            </td>
            <td> {{implode(' | ', $notacredito->descripcion_reglas_generales($regla_id))}}</td>
            <td>  <b>{{number_format((string)$total, 4, '.', ',')}}</b> </td>
            <td>
              
              @if ($total > 0) 
                <div class="text-center be-checkbox be-checkbox-sm has-primary">
                  <input  type="checkbox"
                    class="{{$cod_documento}} input_asignar"
                    id="{{$cod_documento}}" >

                  <label  for="{{$cod_documento}}"
                        data-atr = "ver"
                        class = "checkbox checkbox_asignar"                    
                        name="{{$cod_documento}}"
                  ></label>
                </div>
              @endif

            </td>

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