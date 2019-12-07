@extends('template')
@section('style')

    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/select2/css/select2.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/bootstrap-slider/css/bootstrap-slider.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/bootsnipp.css') }} "/>

@stop
@section('section')

  <div class="be-content asignarregla">
    <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">

              <div class="panel panel-default panel-table">

                  <div class="panel-heading">
                    Lista de Clientes 
                  </div>

                    <form method="GET"  
                          action="{{ url('/gestion-de-regla-del-producto/'.$idopcion) }}" 
                          style="border-radius: 0px;">
                          
                      <div class='filtrotabla row'>
                        <div class="row content ">
                          <div class="col-md-5">
                            <div class="panel panel-contrast">
                              <div class="panel-heading panel-heading-contrast">
                                Filtros
                                <div class="tools">
                                  <div class="btn-group btn-space">

                                    <a href="{{url('/gestion-de-regla-del-producto/'.$idopcion)}}" type="button" class="btn btn-default btn-xs">
                                      <i class="icon mdi mdi-delete"></i>
                                    </a>

                                    <button type="submit" id='btnbuscar' 
                                            class="btn btn-space btn-default btn-social btn-facebook btn-xs"
                                            data-toggle="tooltip" 
                                            data-placement="top" 
                                            title="Buscar">                      
                                      <span class="icon mdi mdi-search"></span>
                                    </button>
                                  </div>
                                </div>
                              </div>
                              <div class="panel-body">


                                <div class="col-xs-12 margen-top-filtro">
                                    <div class="form-group">
                                      <label class="col-sm-12 control-label labelleft" >Cliente :</label>
                                      <div class="col-sm-12 abajocaja" >

                                        {!! Form::select( 'cliente_select', $combolistaclientes, array(),
                                                          [
                                                            'class'       => 'form-control control select2' ,
                                                            'id'          => 'cliente_select',
                                                            'data-aw'     => '1',
                                                          ]) !!}
                                      </div>
                                    </div>
                                </div>


                                <div class="col-xs-12 margen-top-filtro">

                                    <div class="form-group">
                                      <label class="col-sm-12 control-label labelleft" >Producto :</label>
                                      <div class="col-sm-12 abajocaja" >
                                        {!! Form::select( 'producto_select', $combolistaproductos, array(),
                                                          [
                                                            'class'       => 'form-control control select2' ,
                                                            'id'          => 'producto_select',
                                                            'data-aw'     => '2',
                                                          ]) !!}
                                      </div>
                                    </div>
                                </div>


                                <div class="col-xs-12 cajareporte">

                                    <div class="form-group">
                                      <label class="col-sm-12 control-label labelleft" >Tipo:</label>
                                      <div class="col-sm-12 abajocaja" >
                                        {!! Form::select( 'tipoprecio_id', $combotipoprecio_producto, array(),
                                                          [
                                                            'class'       => 'select2 form-control control input-sm' ,
                                                            'id'          => 'tipoprecio_id',
                                                            'required'    => '',
                                                            'data-aw'     => '1',
                                                          ]) !!}
                                      </div>
                                    </div>
                                </div> 

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </form>

                <div class="panel-body" style = "padding-bottom: 20px;">
                  <div class='tablalaravel'>
                    @include('regla.listado.listaasignarregla')
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>


  @include('regla.popover.popoverregla')
  @include('regla.modal.modalregla') 
  </div>


@stop

@section('script')

    <script src="{{ asset('public/js/general/inputmask/inputmask.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/inputmask.extensions.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/inputmask.numeric.extensions.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/inputmask.date.extensions.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/jquery.inputmask.js') }}" type="text/javascript"></script>

    <script src="{{ asset('public/lib/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
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
        $('.asignarregla').tooltip({selector: '[data-toggle="tooltip"]'});

        $.fn.select2.defaults.set('language', 'es');
        // buscar clientes 

        $('#cliente_select').select2({
            // Activamos la opcion "Tags" del plugin
            placeholder: 'Seleccione un cliente',
            language: "es",
            tags: true,
            tokenSeparators: [','],
            ajax: {
                dataType: 'json',
                url: '{{ url("buscarcliente") }}',
                delay: 100,
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function (data, page) {
                  return {
                    results: data
                  };
                },
            }
        });

        // buscar productos 
        $('#producto_select').select2({
            // Activamos la opcion "Tags" del plugin
            placeholder: 'Seleccione un producto',
            language: "es",
            tags: true,
            tokenSeparators: [','],
            ajax: {
                dataType: 'json',
                url: '{{ url("buscarproducto") }}',
                delay: 100,
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function (data, page) {
                  return {
                    results: data
                  };
                },
            }
        });
        $('.dinero').inputmask({ 'alias': 'numeric', 
        'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
        'digitsOptional': false, 
        'prefix': '', 
        'placeholder': '0'});
      });

    </script>


    <script src="{{ asset('public/js/catalogo/regla.js?v='.$version) }}" type="text/javascript"></script> 

@stop