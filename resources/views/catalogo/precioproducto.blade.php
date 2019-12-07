@extends('template')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
@stop
@section('section')
  <div class="be-content precioproducto">
    <div class="main-content container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <!--Dropdowns-->
          <div class="panel panel-default">
            <div class="panel-heading panel-heading-divider">Gestión de precio<span class="panel-subtitle">Se realizara la modificación de precios de cualquier producto</span></div>


            <!--Tabs-->
            <div class="row">
              <!--Success Tabs-->
              <div class="col-sm-12">
                <div class="panel panel-default">
                  <div class="panel-heading">Lista de Productos</div>
                  <div class="tab-container">
                    <ul class="nav nav-tabs nav-tabs-success">
                      <li class="active"><a href="#producto" data-toggle="tab">Productos</a></li>
                    </ul>
                    <div class="tab-content">
                      <div id="producto" class="tab-pane active cont">


                        <table id="table1" class="tablaprecio table table-striped table-striped dt-responsive nowrap listatabla" style='width: 100%;'>

                          <thead>
                            <tr>
                              <th>PRODUCTO</th>
                              <th>U.M.</th>
                              <th class='columna-success-titulo'>PRECIO</th>
                              <th class='columna-warning'>MODIFICAR</th>  
                            </tr>
                          </thead>
                          <tbody>

                            @foreach($productos as $item)
                              <tr data-id ="{{Hashids::encode(substr($item->COD_PRODUCTO, -13))}}"
                                  data-pref ="{{substr($item->COD_PRODUCTO,0,3)}}">
                                <td>{{$item->NOM_PRODUCTO}}</td>
                                <td>{{$item->NOM_UNIDAD_MEDIDA}}</td>
                                <td class='columna-precio @if(is_null($item->precio)) columna-default @else columna-success @endif'>
                                  @if(is_null($item->precio))
                                      0.00
                                  @else 
                                      <i class="mdi mdi-check-circle"></i>
                                      {{$item->precio}}
                                  @endif
                                </td>
                                <td class='columna-warning'>
                                  <input type="text"  
                                         id="precio" 
                                         name="precio"
                                         class="form-control input-sm dinero updateprice"
                                         >
                                </td>
                              </tr>                    
                            @endforeach

                          </tbody>
                        </table>


                      </div>
                      <div id="productoxdepartamento" class="tab-pane cont">

                          <div class="panel-body">
                            <h4 class="xs-mb-20">Departamentos</h4>
                            <div class="row dropdown-showcase">
                              <!--Basic Dropdown-->
                              <div class="showcase col-xs-3">
                                <div class="dropdown">
                                  <ul style="display: block; position: relative;" class="dropdown-menu menu-departamentos">
                                    @foreach($departamentos as $item)
                                       <li ><a href="#"  id="{{$item->COD_CATEGORIA}}" class='selectdepartamento'>{{$item->NOM_CATEGORIA}}</a></li>
                                    @endforeach  
                                  </ul> 
                                </div>
                              </div>

                              <div class="panel panel-default col-xs-8">
                                <div class="panel-body listadoproductos">


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
        </div>
      </div>
    </div>
  </div>
@stop

@section('script')


    <script src="{{ asset('public/js/general/inputmask/inputmask.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/inputmask.extensions.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/inputmask.numeric.extensions.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/inputmask.date.extensions.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('public/js/general/inputmask/jquery.inputmask.js') }}" type="text/javascript"></script>

    <script src="{{ asset('public/lib/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/plugins/buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.html5.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.flash.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.print.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.colVis.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/app-tables-datatables.js') }}" type="text/javascript"></script>



    <script type="text/javascript">
      $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.dataTables();
        $('[data-toggle="tooltip"]').tooltip(); 

        $('.dinero').inputmask({ 'alias': 'numeric', 
        'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 
        'digitsOptional': false, 
        'prefix': '', 
        'placeholder': '0'});

      });
    </script>


    <script src="{{ asset('public/js/catalogo/producto.js?v='.$version) }}" type="text/javascript"></script> 
@stop