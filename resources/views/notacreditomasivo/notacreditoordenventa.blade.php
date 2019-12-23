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
                <div class="panel-heading">Lista de nota de credito masivas
                  <div class="tools">
                    <a href="{{ url('/crear-nota-credito-masiva/'.$idopcion) }}" data-toggle="tooltip" data-placement="top" title="crear nota de credito masiva">
                      <span class="icon mdi mdi-plus-circle-o"></span>
                    </a>
                  </div>
                </div>
                
                <div class="panel-body">

                  <table id="table_group" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Cliente</th>
                        <th>lote</th>
                        <th>Nota de credito</th>
                        <th>Total NC</th>
                        <th>Estado Osiris</th>
                        <th>Boleta Asociada</th>
                        <th>DIV</th>
                        <th>Fecha Crea</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($listadocumentonotacredito as $item)

                        @php 
                          $data_categoria   = $funcion->funciones->data_categoria_documento($item->nota_credito_id);
                          $data_div         = $funcion->funciones->nota_credit_referencia_div($item->nota_credito_id,'TDO0000000000019');
                        @endphp


                        <tr>
                            <td>{{$item->codigo}} </td>

                            <td class="cell-detail">
                              <span class="cell-detail-description-contrato">NC : OTROS</span> 
                              <span class="cell-detail-description-producto">DIV : {{$item->contrato->TXT_EMPR_CLIENTE}}</span>
                            </td>
                            <td>
                              LOTE {{$item->lote}} / OV : {{$item->orden_id}} / {{date_format(date_create($item->fecha_crea), 'd-m-Y')}} / 
                              {{$funcion->funciones->data_centro($item->centro_id)->NOM_CENTRO}}
                            </td>

                            <td class="cell-detail">
                              <span>{{$notacredito->nota_credito_relaciona($item->id)}}</span>
                              <span class="cell-detail-description-producto">Id : {{$item->nota_credito_id}} </span>           
                            </td>


                            <td><b>{{number_format($item->total_notacredito, 4, '.', ',')}}</b> </td>

                            <td>


                              @if($data_categoria->COD_CATEGORIA == 'EDC0000000000001')
                                <span class="badge badge-default">{{$data_categoria->NOM_CATEGORIA}}</span>  
                              @else
                                @if($data_categoria->COD_CATEGORIA == 'EDC0000000000003')
                                  <span class="badge badge-success">{{$data_categoria->NOM_CATEGORIA}}</span>  
                                @else
                                  <span class="badge badge-danger">{{$data_categoria->NOM_CATEGORIA}}</span> 
                                @endif
                              @endif
                            </td>

                            <td class="cell-detail">
                              <span>
                                {{$funcion->funciones->data_documento($item->documento_id)['NRO_SERIE']}} - 
                                {{$funcion->funciones->data_documento($item->documento_id)['NRO_DOC']}}
                              </span>
                              <span class="cell-detail-description-producto">Id : {{$item->documento_id}}</span>           
                            </td>

                            <td class="cell-detail">
                                @if(count($data_div)>0)
                                  <span>
                                    {{$data_div->NRO_SERIE}} - 
                                    {{$data_div->NRO_DOC}}
                                  </span>
                                  <span class="cell-detail-description-producto">Id : {{$data_div->COD_DOCUMENTO_CTBLE}}</span> 
                                @endif
                            </td>
                            <td>{{date_format(date_create($item->fecha_crea), 'd-m-Y')}}</td>
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