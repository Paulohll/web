@extends('template')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/responsive.dataTables.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/select2/css/select2.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/bootstrap-slider/css/bootstrap-slider.css') }} "/>
@stop
@section('section')

  <div class='gestionregla'>

    <div class="main-content container-fluid" style = "padding-top: 0px; padding-bottom: 0px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-table" style = "margin-bottom: 0px;">
                    <div class="titulo_regla" >
                        <h4 class='center'><strong>REGLA ({{$regla->codigo}} - {{strtoupper($regla->nombre)}})</strong></h4>
                         <input type="hidden" value="{{$regla->id}}" id='regla_id'/>
                    </div>
                    <div class="descuento_regla center" >
                        <strong>S/. {{$regla->descuento}}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="be-content">
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Asignar reglas por responsable y producto
                  <div class="tools tooltiptop">


                    <a href="{{url('/eliminar_reglas_masiva')}}" 
                       class='tooltipcss'
                       target="_blank"
                       id="eliminarreglas" 
                       data-href="{{url('/eliminar_reglas_masiva')}}"
                       title="Eliminar reglas">
                       <span class="tooltiptext">Eliminar reglas </span>
                       <span class="icon mdi mdi-delete"></span>
                    </a>

                    <a href="{{url('/asignar_reglas_masiva')}}" 
                       class='tooltipcss'
                       target="_blank"
                       id="asignarreglas" 
                       data-href="{{url('/asignar_reglas_masiva')}}"
                       title="Asignar reglas">
                       <span class="tooltiptext">Asignar reglas </span>
                       <span class="icon mdi mdi-mail-send"></span>
                    </a>

                    <a href="#" class="tooltipcss" id='buscarasignarreglamasiva' >
                      <span class="tooltiptext">Buscar</span>
                      <span class="icon mdi mdi-search"></span>
                    </a>

                  </div>
                </div>
                <div class="panel-body selectfiltro">

                  <div class='filtrotabla row'>
                    <div class="col-xs-12">

                      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cajareporte ind_producto">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Responsable :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'responsable_id', $combo_jefes_ventas, array(),
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'responsable_id',
                                                  'required'    => '',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div>


                      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cajareporte ajax_cliente">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Cliente :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'cliente_id', $combo_cliente, array(),
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'cliente_id',
                                                  'required'    => '',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div>



                      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cajareporte ajax_canal">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Canal :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'canal_id', $combo_canal, array(),
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'canal_id',
                                                  'required'    => '',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div> 


                      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cajareporte ajax_sub_canal">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Sub Canal :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'subcanal_id', $combo_sub_canal, array(),
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'subcanal_id',
                                                  'required'    => '',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div> 

                      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cajareporte ind_producto">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Producto :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'producto_id', $combo_lista_productos_todos, array(),
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'producto_id',
                                                  'required'    => '',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div> 

                      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 cajareporte ind_producto">

                          <div class="form-group">
                            <label class="col-sm-12 control-label labelleft" >Empresa :</label>
                            <div class="col-sm-12 abajocaja" >
                              {!! Form::select( 'empresa_id[]', $combo_lista_empresas, Session::get('empresas')->COD_EMPR,
                                                [
                                                  'class'       => 'select2 form-control control input-sm' ,
                                                  'id'          => 'empresa_id',
                                                  'required'    => '',
                                                  'multiple'    => 'multiple',
                                                  'data-aw'     => '1',
                                                ]) !!}
                            </div>
                          </div>
                      </div>
                    
                  </div>

                  <div class="col-xs-12">
                    <div class='listacontratomasiva listajax reporteajax'>
                        <div class='ajaxvacio'>
                          Seleccione un responsable y producto ...

                        </div>
                    </div>
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

  <script src="{{ asset('public/js/catalogo/gestionregla.js?v='.$version) }}" type="text/javascript"></script> 
@stop