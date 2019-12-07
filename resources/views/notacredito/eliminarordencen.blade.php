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

                <div class="panel-heading">Elimnar orden cen

                </div>
                <div class="panel-body selectfiltro">

                  <div class="col-xs-12">


                    <div class="main-content container-fluid" style="padding-bottom: 0px;">
                      <!--Basic forms-->
                      <div class="row">

                        <div class="col-sm-6">
                          <div class="panel panel-default panel-border-color panel-border-color-primary">
                            <div class="panel-heading panel-heading-divider">Cliente<span class="panel-subtitle">Información de la reglas asociadas</span></div>
                            <div class="panel-body" style = 'padding-left: 20px;'>
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
                                    <b>Total nota credito  : </b>
                                    <label class='totales totalnotacredito'>{{number_format($documentonotacredito->total_reglas, 4, '.', ',')}}</label>
                                  </div>
                            </div>
                          </div>
                        </div>


                        <div class="col-sm-6">
                          <div class="panel panel-default panel-border-color panel-border-color-primary">
                            <div class="panel-heading panel-heading-divider">Nota credito<span class="panel-subtitle">Información de la nota credito asociada</span></div>
                            <div class="panel-body" style = 'padding-left: 20px;'>

                                  @php
                                    $documento          =  $notacredito->nota_credito_asociada($documentonotacredito->id);
                                  @endphp


                                  <div class="from-texto form-group xs-pt-10">
                                    <b>Serie - Número : </b>
                                    <label>
                                      @if(count($documento) >0) 
                                        {{$documento->NRO_SERIE}}-{{$documento->NRO_DOC}}
                                      @endif                                      
                                    </label>
                                  </div>
                                  <div class="from-texto form-group">
                                    <b>Motivo : </b>
                                    <label>
                                      @if(count($documento) >0) 
                                        {{$notacredito->nombre_motivo($documento->COD_CATEGORIA_MOTIVO_EMISION)->NOM_CATEGORIA}}
                                      @endif 
                                    </label>
                                    <input id="contrato_id" name="contrato_id" type="hidden" value="{{$contrato->COD_CONTRATO}}">
                                  </div>
                                  <div class="from-texto form-group">
                                    <b>Glosa : </b>
                                    <label>
                                      @if(count($documento) >0) 
                                        {{$documento->TXT_GLOSA}}
                                      @endif 
                                    </label>
                                    <input id="direccion_id" name="direccion_id" type="hidden" value="{{$direccion->COD_DIRECCION}}">
                                  </div>

                                  <div class="from-texto form-group">
                                    <b>Información Adicional  : </b>
                                    <label class='totales totalfactura'>
                                      @if(count($documento) >0) 
                                        {{$documento->TXT_INFO_ADICIONAL}}
                                      @endif 
                                    </label>
                                  </div>

                            </div>
                          </div>
                        </div>

 

                      </div>
                    </div>




                      <table id="tablenotacreditoeliminar" class="table table-striped table-hover table-fw-widget listatabla">
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
                            <th >Eliminar</th>

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
                                    data_documento_nota_credito='{{$iddocumentonotacredito}}'
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

                                    @if(count($factura))              
                                        <span class="badge badge-primary badgeelimnar">
                                            <span class="mdi mdi-delete"></span>
                                        </span>
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