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
                <div class="panel-heading">Regla de negociación

                  <div class="tools">
                    <a href="{{ url('/agregar-regla-negociacion/'.$idopcion) }}" data-toggle="tooltip" data-placement="top" title="Crear negociacion del precio del producto">
                      <span class="icon mdi mdi-plus-circle-o"></span>
                    </a>
                  </div>
                  <span class="panel-subtitle">Se puede bajar el precio del producto hasta un determinado valor y poder negociar</span>
                </div>
                <div class="panel-body">
                  <table id="tablenegociaciones" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Codigo</th>
                        <th>Monto</th>
                        <th>Utilizada</th>
                        <th>Fecha inicio</th>
                        <th>Fecha de expiración</th>
                        <th>Departamento</th>
                        <th>Cantidad Minima</th>
                        <th>Estado</th>
                        <th>Opción</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach($listanegociacion as $item)
                        <tr>
                          <td>{{strtoupper($item->nombre)}}</td>
                          <td>{{$item->codigo}}</td>
                          <td>

                            @if($item->tipodescuento == 'POR') 
                              %
                            @else 
                              S/.
                            @endif
                            {{number_format($item->descuento, 4, '.', ',')}}
                          </td>


                          <td>
                            <span class="badge badge-default">{{$item->cantidadutilizada}}</span>
                          </td>
                          <td>{{date_format(date_create($item->fechainicio), 'd-m-Y H:i')}}</td>
                          <td>
                            @if($item->fechafin == $fechavacia) 
                              <span class="badge badge-default">ilimitado</span> 
                            @else 
                              {{date_format(date_create($item->fechafin), 'd-m-Y H:i')}}
                            @endif
                          </td>

                          <td>
                            @if(trim($item->departamento_id) == '') 
                              <span class="badge badge-default">TODOS</span> 
                            @else 
                              <span class="badge badge-danger">{{$funcion->funciones->departamento($item->departamento_id)->NOM_CATEGORIA}}</span>
                            @endif
                          </td>
                          <td>
                              <span class="badge badge-default">{{$item->cantidadminima}}</span>
                          </td>
                          <td> 
                            @if($item->estado == 'PU') 
                              <span class="badge badge-success">PUBLICADO</span>
                            @else 
                              @if($item->estado == 'NP') 
                                <span class="badge badge-warning">NO PUBLICADO</span>
                              @else
                                <span class="badge badge-danger">CERRADO</span> 
                              @endif
                            @endif
                          </td>
                          <td class="rigth">
                            
                            <div class="btn-group btn-hspace">
                              <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <ul role="menu" class="dropdown-menu pull-right">
                                
                                <!--
                                <li>
                                  <a href="{{ url('/modificar-regla-precio/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                    Modificar
                                  </a>  
                                </li>
                                -->

                                <li>
                                  <a href="{{ url('/gestion-masiva-regla-precio/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                    Gestión masiva
                                  </a>  
                                </li>


                              </ul>
                            </div>
                            
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
	<script src="{{ asset('public/js/app-tables-datatables.js?v='.$version) }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.dataTables();
        $('[data-toggle="tooltip"]').tooltip(); 
      });
    </script> 
@stop