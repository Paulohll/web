<div class="main-content container-fluid" style="padding-bottom: 0px;">
  <!--Basic forms-->


  <div class="row">

    <form method="POST" action="{{ url('/agregar-nota-credito') }}" style="border-radius: 0px;" class="group-border-dashed">
          {{ csrf_field() }}


      <div class="col-sm-12">
          <h2 style="font-size: 14px;text-align: center;"><b>GENERAR NOTA DE CREDITO</b></h2>
          <br>

          <div class="row xs-pt-15 btnguardar">
            <div class="col-xs-6">
                <div class="be-checkbox">
                </div>
            </div>
            <div class="col-xs-12">
              <p class="text-right">
                <button type="submit" id='btnguardar' class="btn btn-space btn-primary">Guardar</button>
              </p>
            </div>
          </div>

      </div>

      <div class="col-sm-4">
        <div class="panel panel-default">
          <div class="panel-body">

              <div class="from-texto form-group xs-pt-10">
                <b>Empresa : </b>
                <label>{{$contrato->NOM_EMPR}}</label>
              </div>
              <div class="from-texto form-group">
                <b>Cuenta : </b>
                <label>{{$contrato->CONTRATO}}</label>
                <input id="contrato_id" name="contrato_id" type="hidden" value="{{$contrato->COD_CONTRATO}}">
              </div>
              <div class="from-texto form-group">
                <b>Dirección : </b>
                <label>{{$direccion->NOM_DIRECCION}}</label>
                <input id="direccion_id" name="direccion_id" type="hidden" value="{{$direccion->COD_DIRECCION}}">
              </div>
              <div class="from-texto form-group">
                <b>Fecha emisión  : </b>
                <label>{{$funcion->fin}}</label>
              </div>

              <div class="from-texto form-group">
                <b>Total factura  : </b>
                <label class='totales totalfactura'>0.0000</label>
              </div>

              <div class="from-texto form-group">
                <b>Total nota credito  : </b>
                <label class='totales totalnotacredito'>0.0000</label>
              </div>


              <input type="hidden" id='facturasnotacredito' name='facturasnotacredito'>
              <input type="hidden" id='facturasrelacionada' name='facturasrelacionada'>
              <input type="hidden" id='idopcion' name='idopcion'>

          </div>
        </div>
      </div>



      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 cajareporte">

          <div class="form-group xs-pt-10" style="margin-bottom: 0px;">
            <label class="col-sm-12 control-label labelleft" >Serie :</label>
            <div class="col-sm-8 abajocaja" style='padding-right: 0px;'>
              {!! Form::select( 'serie', $combo_series, array(),
                                [
                                  'class'       => 'select2 form-control control input-sm' ,
                                  'id'          => 'serie',
                                  'required'    => '',
                                  'data-aw'     => '1',
                                ]) !!}

            </div>
            <div class="input-group-btn open nrodocumento">
                <label class="col-sm-12 control-label labelleft nro-documento" style="margin-top: 13px;">00000000</label>
            </div>


          </div>

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

      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 cajareporte">

          <div class="form-group xs-pt-10">
            <label class="col-sm-12 control-label labelleft" >Glosa :</label>
            <div class="col-sm-12 abajocaja" >
              <input  type="text"
                      id="glosa" name='glosa' 
                      value=""
                      placeholder="Glosa"
                      class="form-control input-md"
                      autocomplete="off"/>

            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-12 control-label labelleft" >Informacon Adicional :</label>
            <div class="col-sm-12 abajocaja" >
              <input  type="text"
                      id="informacionadicional" name='informacionadicional' 
                      value=""
                      placeholder="Informacion Adicional"
                      class="form-control input-md"
                      autocomplete="off"/>
            </div>
          </div>
      </div> 


    </form>



  </div>
</div>




<table id="tablenotacredito" class="table table-striped table-hover table-fw-widget listatabla">
  <thead>
    <tr> 
      <th class= 'tabladp'>FECHA</th>
      <th class= 'tabladp'>TIPO DOCUMENTO</th>
      <th class= 'tabladp'>N°. FACTURA</th>
      <th class= 'tabladp'>ORDEN CEN</th>
      <th class= 'tabladp'>TOTAL</th>
      <th class= 'tabladp'>ESTADO</th>
      <th class="columna_2"></th>
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
      <th>

      </th>

    </tr>
  </thead>
  <tbody>
    @foreach($listadocumentos as $index => $item)

          @php
            $total          =  $notacredito->monto_descuento_nota_credito($item->COD_DOCUMENTO_CTBLE,$item->TXT_REFERENCIA);
          @endphp



          <tr class='fila_regla'
              data_contrato='{{$item->COD_CONTRATO_RECEPTOR}}'
              data_documento='{{$item->COD_DOCUMENTO_CTBLE}}'
              data_referencia='{{$item->TXT_REFERENCIA}}'
              data_tf='{{$item->CAN_TOTAL}}'
              data_tnc='{{$total}}'
              >
              <td>{{date_format(date_create($item->FEC_EMISION), 'd-m-Y')}}</td>
              <td>{{$item->TXT_CATEGORIA_TIPO_DOC}}</td>
              <td>{{$item->NRO_SERIE}}-{{$item->NRO_DOC}}</td>

              <td>{{$notacredito->orden_cen_documento($item->TXT_REFERENCIA)->NRO_ORDEN_CEN}}</td>

              <td class='center bold tf'>{{number_format($item->CAN_TOTAL, 4, '.', ',')}}</td>
              <td>{{$item->TXT_CATEGORIA_ESTADO_DOC_CTBLE}}</td>
              <td>
                <span class="badge badge-primary badgenotacredito">
                    <span class="mdi mdi-eye"></span>
                </span>
              </td>

              <td class='center bold tnc'>{{number_format($total, 4, '.', ',')}} </td>
              <td>
                  @if ($total > 0) 
                    <div class="text-center be-checkbox be-checkbox-sm has-primary">
                      <input  type="checkbox"
                        class="{{$item->COD_DOCUMENTO_CTBLE}} input_asignar"
                        id="{{$item->COD_DOCUMENTO_CTBLE}}" >

                      <label  for="{{$item->COD_DOCUMENTO_CTBLE}}"
                            data-atr = "ver"
                            class = "checkbox checkbox_asignar"                    
                            name="{{$item->COD_DOCUMENTO_CTBLE}}"
                      ></label>
                    </div>
                  @endif
              </td>

              <td>

                  @if ($total > 0) 
                    <div class="be-radio has-success">
                      <input type="radio" name="factura" id="rad{{$index}}" class='input_asignar_radio'>
                      <label for="rad{{$index}}"></label>
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