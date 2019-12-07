@extends('template')
@section('style')


    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/responsive.dataTables.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/select2/css/select2.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/bootstrap-slider/css/bootstrap-slider.css') }} "/>
@stop
@section('section')

  <div class="be-content notacredito">
    <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">

                <div class="panel-heading">Generar nota de credito

                </div>
                <div class="panel-body selectfiltro">

                  <div class="col-xs-12">


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
                                    <b>Total factura  : </b>
                                    <label class='totales totalfactura'>{{number_format($documentonotacredito->total_factura, 4, '.', ',')}}</label>
                                  </div>

                                  <div class="from-texto form-group">
                                    <b>Total descuento  : </b>
                                    <label class='totales totalnotacredito'>{{number_format($documentonotacredito->total_reglas, 4, '.', ',')}}</label>
                                  </div>

                                  <input type="hidden" id='facturasrelacionada' name='facturasrelacionada'>
                                  <input type="hidden" id='documentonotacredito_id' name='documentonotacredito_id' value = '{{$documentonotacredito->id}}'>
                                  <input type="hidden" id='idopcion' name='idopcion' value = '{{$idopcion}}'>
                                  <input type="hidden" id='total_reglas' name='total_reglas' value='{{$documentonotacredito->total_reglas}}'>


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
                            <th class= 'tabladp'>ORDEN CEN</th>
                            <th class= 'tabladp'>N°. FACTURA</th>

                            <th class= 'tabladp'>TOTAL RECIBIDO</th>
                            <th class= 'tabladp'>ESTADO</th>
                            <th class="columna_2"></th>


                            <th class= 'warning'>REGLAS</th>
                            <th class= 'warning'>NOTA CREDITO</th>
                            <th>

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






                  </div>

                </div>
              </div>
            </div>
          </div>
    </div>

  @include('notacredito.modal.modaldetalledocumento')

  </div>

@stop

@section('script')


  <script src="{{ asset('public/lib/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/plugins/buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/js/responsive.bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/js/app-tables-datatables.js?v='.$version) }}" type="text/javascript"></script>

  <script src="{{ asset('public/lib/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/jquery.nestable/jquery.nestable.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/moment.js/min/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/select2/js/select2.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/bootstrap-slider/js/bootstrap-slider.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/js/app-form-elements.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/parsley/parsley.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/jquery.niftymodals/dist/jquery.niftymodals.js') }}" type="text/javascript"></script>

    <script type="text/javascript">


      $.fn.niftyModal('setDefaults',{
        overlaySelector: '.modal-overlay',
        closeSelector: '.modal-close',
        classAddAfterOpen: 'modal-show',
      });

      $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.formElements();
        App.dataTables();
        $('[data-toggle="tooltip"]').tooltip();
        $('form').parsley();

      });
    </script> 

    <script src="{{ asset('public/js/notacredito/notacredito.js?v='.$version) }}" type="text/javascript"></script> 
@stop