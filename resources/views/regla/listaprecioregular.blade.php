@extends('template')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
@stop
@section('section')


	<div class="be-content">
		<div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Lista de precio regular
                  <div class="tools">
                    <a href="{{ url('/agregar-regla-precio-regular/'.$idopcion) }}" data-toggle="tooltip" data-placement="top" title="Crear regla de precio regular">
                      <span class="icon mdi mdi-plus-circle-o"></span>
                    </a>


                  </div>
                </div>
                <div class="panel-body">
                  <table id="tablecupones" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>Codigo</th>                                             
                        <th>Nombre</th>
                        <th>Departamento</th>                        
                        <th>Precio regular</th>
                        <th>Opción</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach($listaprecioregular as $item)
                        <tr>
                          <td>{{$item->codigo}}</td>                          
                          <td>{{$item->nombre}}</td>
                          <td>{{$funcion->funciones->departamento($item->departamento_id)->NOM_CATEGORIA}}</td>
                          <td>{{number_format($item->descuento, 2, '.', ',')}}</td>

                          
                          <td class="rigth"><!--
                            <div class="btn-group btn-hspace">
                              <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              
                              <ul role="menu" class="dropdown-menu pull-right">
                                <li>
                                  <a href="{{ url('/modificar-regla-precio-regular/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                    Modificar
                                  </a>  
                                </li>
                              </ul>
                              
                            </div>-->
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

@stop

@section('script')


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
      });
    </script> 
@stop