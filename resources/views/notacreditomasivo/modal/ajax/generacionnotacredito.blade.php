<div class="modal-header">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title"><strong>{{$funcion->funciones->data_cliente($cuenta_id)->NOM_EMPR}}</strong></h3>
  <h5 class="modal-title">Motivo : {{$funcion->funciones->data_categoria($motivo_id)->NOM_CATEGORIA}}</h5>
  <h5 class="modal-title">Informacion Adicional : {{$informacionadicional}}</h5>
  <h5 class="modal-title">GLOSA : {{$funcion->funciones->data_categoria($motivo_id)->NOM_CATEGORIA}}</h5>
  <h5 class="modal-title @if($validacion_cantidad_productos == 1 ) color_danger @endif center ">{{$mensaje_validacion}}</h5>

</div>
<div class="modal-body">
  <div class="scroll_text">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <table id="tabladetalleproductonc_generacion" class="table table-striped table-hover table-fw-widget dt-responsive nowrap listatabla" style='width: 100%;'>
          <thead>
            <tr>
              <th>Nª</th>
              <th>Nota Credito</th>
              <th>Empresa</th>
              <th>Boleta Asociada</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @php 
              $totales        = 0;
            @endphp
            @foreach($lista_detalle_producto as $index => $item)

                @php $totales    = $totales + $item['total']; @endphp
                <tr class='filacondetalledocumento filaseleccionada'
                    data_array_productos    = "{{json_encode($item['detalle_productos'])}}"
                    data_documento_id       = "{{$item['documento_id']}}"
                    data_serie_correlativo  = "{{$item['serie_correlativo']}}"
                    >
                  <td>{{$index + 1}}</td>
                  <td> 
                    {{$serie}} - {{$notacredito->numero_documento_conteo($serie,'TDO0000000000007',$index+1)}}
                  </td> 
                  <td>{{$funcion->funciones->data_cliente($cuenta_id)->NOM_EMPR}}</td>
                  <td>{{$item['serie_correlativo']}}</td>
                  <td class='right'>{{number_format($item['total'], 4, '.', ',')}}</td>
                </tr>

            @endforeach
          </tbody>
          <tfooter>
            <tr>
            <th colspan="4"></th>
              <th class='right'>{{number_format($totales, 4, '.', ',')}}</th>
            </tr>
          </tfooter>
        </table>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class='lista_nc_detalle_documento'>
          
        </div>
      </div>

      
    </div>
  </div>
</div>

<form method="POST" action="{{ url('/crear-nota-credito-masiva/'.$idopcion) }}">
  {{ csrf_field() }}

  <input type="hidden" id='array_lista_detalle_producto' name='array_lista_detalle_producto' value='{{json_encode($lista_detalle_producto)}}'>
  <input type="hidden" id='cuenta_id' name='cuenta_id' value='{{$cuenta_id}}'>
  <input type="hidden" id='motivo_id' name='motivo_id' value='{{$motivo_id}}'>
  <input type="hidden" id='glosa' name='glosa' value='{{$funcion->funciones->data_categoria($motivo_id)->NOM_CATEGORIA}}'>
  <input type="hidden" id='serie' name='serie' value='{{$serie}}'>
  <input type="hidden" id='informacionadicional' name='informacionadicional' value='{{$informacionadicional}}'>
  <input type="hidden" id='data_cod_orden_venta' name='data_cod_orden_venta' value='{{$data_cod_orden_venta}}'>
  <input type="hidden" id='validacion_cantidad_productos' name='validacion_cantidad_productos' value='{{$validacion_cantidad_productos}}'>




  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
    <button type="submit" data-dismiss="modal" class="btn btn-success" id="crearnotacredito_osiris">Crear nota de credito</button>
  </div>
</form>
