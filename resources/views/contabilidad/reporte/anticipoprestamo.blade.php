@extends('template')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/responsive.dataTables.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/select2/css/select2.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/bootstrap-slider/css/bootstrap-slider.css') }} "/>
@stop
@section('section')

  <div class="be-content">
    <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">

                <div class="panel-heading">Anticipo y prestamo - Nota de Ingreso
                  <div class="tools tooltiptop">


                    <a href="{{url('/anticipo-prestamo-masivo-pdf')}}" 
                        target="_blank"
                        class='tooltipcss'
                        id="descargaranticipoprestamopdf" 
                        data-href="{{url('anticipo-prestamo-masivo-pdf')}}"
                        title="Descargar pdf">
                        <span class="tooltiptext">Descargar pdf </span>
                        <span class="icon mdi mdi-collection-pdf"></span>
                    </a>



                  </div>
                </div>
                <div class="panel-body selectfiltro">

                  <div class='filtrotabla row'>
                    <div class="col-xs-12">

                      <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 cajareporte">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Centro :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'centro_id', $combo_lista_centros, array(),
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'centro_id',
                                                  'required'    => '',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div> 


                      <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 cajareporte">

                          <div class="form-group ">
                            <label class="col-sm-12 control-label labelleft" >Fecha Inicio:</label>
                            <div class="col-sm-12 abajocaja" >
                              <div data-min-view="2" 
                                     data-date-format="dd-mm-yyyy"  
                                     class="input-group date datetimepicker pickerfecha" style = 'padding: 0px 0;margin-top: -3px;'>
                                     <input size="16" type="text" 
                                            value="{{$inicio}}" 
                                            placeholder="Fecha Inicio"
                                            id='fechainicio' 
                                            name='fechainicio' 
                                            required = ""
                                            class="form-control"/>
                                      <span class="input-group-addon btn btn-primary"><i class="icon-th mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                          </div>
                      </div> 

                      <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 cajareporte">

                          <div class="form-group ">
                            <label class="col-sm-12 control-label labelleft" >Fecha Fin:</label>
                            <div class="col-sm-12 abajocaja" >
                              <div data-min-view="2" 
                                     data-date-format="dd-mm-yyyy"  
                                     class="input-group date datetimepicker pickerfecha" style = 'padding: 0px 0;margin-top: -3px;'>
                                     <input size="16" type="text" 
                                            value="{{$hoy}}" 
                                            placeholder="Fecha Fin"
                                            id='fechafin' 
                                            name='fechafin' 
                                            required = ""
                                            class="form-control"/>
                                      <span class="input-group-addon btn btn-primary"><i class="icon-th mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                          </div>
                      </div> 

                  </div>

                  <div class="col-xs-12">
                    <div class='listaprecioproducto listajax reporteajax'>
                        <div class='ajaxvacio'>
                          Imprima las nota de ingreso ...

                        </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
    </div>
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


    <script type="text/javascript">
      $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.formElements();
        App.dataTables();
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script> 

    <script src="{{ asset('public/js/reporte/contabilidad.js?v='.$version) }}" type="text/javascript"></script> 
@stop