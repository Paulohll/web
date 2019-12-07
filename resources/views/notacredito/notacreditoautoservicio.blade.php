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
                <div class="panel-heading">Lista de nota de credito autoservicio
                  <div class="tools">
                    <a href="{{ url('/agregar-reglas-orden-cen/'.$idopcion) }}" data-toggle="tooltip" data-placement="top" title="Agregar reglas de nota de credito a la orden cen">
                      <span class="icon mdi mdi-plus-circle-o"></span>
                    </a>
                  </div>
                </div>
                <div class="panel-body">
                  <table id="table1" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Cliente</th>

                        <th>Total factura</th>
                        <th>Total descuento</th>
                        <th>Total nota de credito</th>

                        <th>Nota de credito</th>
                        <th>Fecha Crea</th>
                        <th>Estado</th>
                        <th>Opción</th>
                      </tr>
                    </thead>
                    <tbody>


                      @foreach($listadocumentonotacredito as $item)
                        <tr>
                            <td>{{$item->codigo}} </td>
                            <td>{{$item->contrato->TXT_EMPR_CLIENTE}}</td>
                            <td><b>{{number_format($item->total_factura, 4, '.', ',')}}</b> </td>
                            <td><b>{{number_format($item->total_reglas, 4, '.', ',')}}</b> </td>
                            <td><b>{{number_format($item->total_notacredito, 4, '.', ',')}}</b> </td>

                            <td>{{$notacredito->nota_credito_relaciona($item->id)}}</td>
                            <td>{{date_format(date_create($item->fecha_crea), 'd-m-Y')}}</td>

                            <td> 
                              @if($item->estado == 'EM') 
                                <span class="badge badge-success">GENERADO</span>
                              @else 
                                @if($item->estado == 'CE') 
                                  <span class="badge badge-danger">EMITIDO</span> 
                                @endif
                              @endif
                            </td>

                            <td class="rigth">
                              <div class="btn-group btn-hspace">
                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                  <li>
                                    <a href="{{ url('/agregar-orden-cen/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                      Agregar orden cen
                                    </a>
                                  </li>
                                  <li>
                                    <a href="{{ url('/eliminar-orden-cen/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                      Eliminar orden cen
                                    </a>
                                  </li>
                                  <li>
                                    <a href="{{ url('/ver-asignacion-nota-credito/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                      Ver la Asiganción Nota de credito
                                    </a>
                                  </li>
                                  <li>
                                    <a href="{{ url('/asociar-nota-credito/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                                      Asociar Nota de credito
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