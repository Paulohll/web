<div class="row">

  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="panel-heading">Generacion de Nota de Crédito ({{$funcion->funciones->data_cliente($cuenta_id)->NOM_EMPR}})
      <div class="tools tooltiptop">
      </div>

          <input type="hidden" name="cuenta_id_m" id='cuenta_id_m' value = {{$cuenta_id}}>
          <input type="hidden" name="data_cod_orden_venta_m" id='data_cod_orden_venta_m' value = {{$data_cod_orden_venta}}>
    </div>
  </div>

  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

    @php $tiene_asociadas  =   0; @endphp
    <table id="tabladocumentosasociados" class="table table-striped table-hover table-fw-widget dt-responsive nowrap listatabla" style='width: 100%;'>
      <thead>
        <tr> 
          <th>Nª</th>
          <th>Documento</th>
          <th>Fecha emision</th>
          <th>Sub Total</th>
          <th>Igv</th>
          <th>Total</th>
          <th>Ver</th>
        </tr>
      </thead>
      <tbody>
        @foreach($lista_documento_boletas as $index => $item)

            @php 
              $nota_credito_asociada    =   $funcion->funciones->boleta_o_factura_asociada_nota_credito($item->COD_DOCUMENTO_CTBLE,'TDO0000000000007');
              $color                    =   '';
            @endphp

            @if(count($nota_credito_asociada)>0)
              @php 
                $color                  =   $funcion->funciones->ind_faltante_en_boletas_nota_credito($item->COD_DOCUMENTO_CTBLE);
                $tiene_asociadas        =   1;
              @endphp
            @endif


            <tr class='{{$color}}'>
              <td>{{$index + 1}}</td>

              <td class="cell-detail">
                <b>
                  <span>{{$item->NRO_SERIE}} - {{$item->NRO_DOC}} </span>
                  <span class="cell-detail-description-contrato"> CLIENTE : {{$item->TXT_EMPR_RECEPTOR}}</span>
                  @foreach($nota_credito_asociada as $itemnc)
                    <span class="cell-detail-description-producto">NC : {{$itemnc->NRO_SERIE}} - {{$itemnc->NRO_DOC}}</span>       
                  @endforeach
                </b>
              </td>

              <td>{{date_format(date_create($item->FEC_EMISION), 'd-m-Y')}}</td>
              <td>{{$item->CAN_SUB_TOTAL}}</td>
              <td>{{$item->CAN_IMPUESTO_VTA}}</td>
              <td class='right'>{{$item->CAN_TOTAL}}</td>
              <td>
                <span class="badge badge-primary btn-eyes btn-detalle-producto" 
                      data-documento-id="{{$item->COD_DOCUMENTO_CTBLE}}">
                  <span class="mdi mdi-eye  md-trigger"></span>
                </span>
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>


  </div>


  <input type="hidden" name="tiene_asociadas" id='tiene_asociadas' value = {{$tiene_asociadas}}>
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

    <div class="row">
      <div class="col-xs-6">

          <div class="form-group">
            <label class="col-sm-12 control-label labelleft" >Serie :</label>
            <div class="col-sm-12 abajocaja">
              {!! Form::select( 'serie', $combo_series, array(),
                                [
                                  'class'       => 'select2 form-control control input-sm' ,
                                  'id'          => 'serie',
                                  'required'    => '',
                                  'data-aw'     => '1',
                                ]) !!}

            </div>
          </div>

      </div>
      <div class="col-xs-6">
          <div class="form-group">
            <label class="col-sm-12 control-label labelleft" >Motivo :</label>
            <div class="col-sm-12 abajocaja" >
              {!! Form::select( 'motivo_id', $combo_motivos, array(),
                                [
                                  'class'       => 'select2 form-control control input-sm' ,
                                  'id'          => 'motivo_id',
                                  'required'    => '',
                                  'data-aw'     => '1',
                                ]) !!}
            </div>
          </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-xs-12">

        <table id="tabladetalleproductonc" class="table table-striped table-hover table-fw-widget dt-responsive nowrap listatabla" style='width: 100%;'>
          <thead>
            <tr> 

              <th>Material / Servicio</th>
              <th>Cantidad</th>
              <th>Precio</th>
              <th>Total</th>
              <th></th>

            </tr>
          </thead>
          <tbody class='listatabladetalle'>
            @while ($row = $lista_detalle_producto->fetch())
              <tr class= 'fila_producto'
                  data_producto_id = "{{$row['COD_PRODUCTO']}}"
              >
                <td class="cell-detail">
                  <span>{{$row['TXT_NOMBRE_PRODUCTO']}}</span>
                  <span class="cell-detail-description-producto">{{$row['UNIDAD_MEDIDA']}}</span>
                </td>
                <td class= 'columna-cantidad'
                    data_cantidad_original = "{{$row['CAN_PRODUCTO']}}">
                      <input  type="text"
                              id="cantidad" 
                              name='cantidad' 
                              value="0" 
                              placeholder="Cantidad"
                              required = ""
                              autocomplete="off" 
                              class="form-control input-md dinero updatecantidad"/>
                </td>
                <td class= 'columna-precio'>{{$row['CAN_PRECIO_UNIT']}}</td>
                <td class= 'columna-importe'>0.0000</td>

                <td class= 'columna-eliminar'>
                  <span class="mdi mdi-close-circle mdi-close-fila-tabla"></span>
                </td>

              </tr>                    
            @endwhile
          </tbody>
          <tfooter>
            <tr>
            <th colspan="3"></th>
              <th class='total_nota_credito'>0.0000</th>
              <th></th>
            </tr>
          </tfooter>
        </table>
      </div>
    </div>
    <br>
    <div class="row">
      
      <div class="col-xs-12">
        <div class="form-group">

          <label class="col-sm-12 control-label labelleft" >Informacion adicional : </label>

          <div class="col-sm-12 abajocaja" >
            <input  type="text"
                    id="informacionadicional" name='informacionadicional' 
                    value=""
                    placeholder="Información Adicional"
                    class="form-control input-md"
                    autocomplete="off"/>

          </div>
        </div>
      </div>

      <div class="col-xs-12">

        <div class="form-group">
          <div class="col-sm-12 abajocaja right">
            <button class="btn btn-space btn-primary generar_nota_credito">
              Generar nota de credito
            </button> 
          </div>
        </div>
      </div>        
    </div>

  </div>







</div>


<script type="text/javascript">
  $(document).ready(function(){
    $('.dinero').inputmask({ 'alias': 'numeric', 
    'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
    'digitsOptional': false, 
    'prefix': '', 
    'placeholder': '0'});
  });
</script> 
