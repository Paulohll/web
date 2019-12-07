<div class="panel panel-contrast">
<div class="panel-heading panel-heading-contrast">
      <strong class='c_nombre_cliente'>{{$data_ncl}}</strong>
      <span class="panel-subtitle c_documento-cuenta">{{$data_dcl}}</span>
      <span class="panel-subtitle c_documento-cuenta">{{$data_ccl}}</span>                           
      <span class="mdi mdi-close-circle mdi-close-cliente"></span>
      <span class="mdi mdi-check-circle mdi-check-cliente"
          data_icl='{{$data_icl}}'
          data_pcl='{{$data_pcl}}'
          data_icu='{{$data_icu}}'
          data_pcu='{{$data_pcu}}'
          data_ncl='{{$data_ncl}}'
          data_dcl='{{$data_dcl}}'
          data_ccl='{{$data_ccl}}'
      ></span>
      <br>
      <div class="form-group">
        <div class="chart-legend">
          <table>
            <tbody>
              <tr>
                <td class="chart-legend-color"><span data-color="top-sales-color1" style="background-color: rgb(52, 168, 83);"></span></td>
                <td class="pull-left">Clasificacion</td> <td class="chart-legend-value">
                @if(count($reglacredito) <= 0)
                  <span class="badge badge-default"> - </span> 
                @else
                  @if($reglacredito[0]->clasificacion  === 'A') 
                    <span class="badge badge-success">A</span> 
                  @elseif($reglacredito[0]->clasificacion  === 'B')
                    <span class="badge badge-warning">B</span>
                  @elseif($reglacredito[0]->clasificacion  === 'M'  )
                  <span class="badge badge-danger">M</span>
                  @elseif($reglacredito[0]->clasificacion  === 'C'  )
                  <span class="badge badge-info">C</span>
                  @endif
                @endif
                </td>
             </tr> 
             <tr>
               <td class="chart-legend-color"><span data-color="top-sales-color2" style="background-color: rgb(251, 188, 5);"></span></td>
               <td  class="pull-left"> Condición de Pago</td> <td class="chart-legend-value">
               @if(count($reglacredito) <= 0 )
                  <span class="badge badge-default"> CONTADO </span> 
                @else
                  @if ( empty($reglacredito[0]->condicionpago_id))
                  <span class="badge badge-default"> CONTADO </span> 
                  @else
                  <span class="badge badge-info"> {{$reglacredito[0]->tipopago->NOM_CATEGORIA}} </span>
                  @endif
                  
                @endif
                </td>
             </tr>
             <tr>
               <td class="chart-legend-color"><span data-color="top-sales-color3" style="background-color: rgb(66, 133, 244);"></span></td>
               <td  class="pull-left">Límite de Crédito</td> <td class="chart-legend-value">
               @if(count($reglacredito) <= 0)
                    0
                @else
                  {{$reglacredito[0]->canlimitecredito}}
                @endif
                </td>  
               
             </tr>
             <tr>
               <td class="chart-legend-color"><span data-color="top-sales-color3" style="background-color: rgb(235, 99, 87);"></span></td>
               <td  class="pull-left">Deuda</td> <td class="chart-legend-value">
                 @if(count($saldocli) <= 0)
                    0
                 @else
                 <?php $sum = 0; ?>
                 @foreach($saldocli as $item)
                 <?php $sum += $item->SALCON; ?>
                 @endforeach
                 {{ $sum }}
                 @endif
                </td>  
               
             </tr>
            </tbody>
          </table>
        </div>
          
      </div>
</div>
  <div id="canvasdeuda" class="text-center panel-heading panel-heading-contrast" style="
    padding-top: 0 !important;
    padding-bottom: 0 !important;"></div>
  <div id="canvasdeudacont" style="background-color: #f5f5f5;">
    <canvas id="deudatramo"></canvas>
    <!-- <div class="panel-heading panel-heading-contrast">
      <div class="aside-compose"><a  href="#pedidocontainer" class="btn btn-lg btn-primary btn-block">Registrar Pedido</a></div>
    </div> -->
  </div>
  
</div>
<div class="panel-body">
  

    <div class="col-xs-12 margen-top-filtro"  id="pedidocontainer">

         <div class="form-group">
           <label class="col-sm-12 control-label labelleft"> Fecha de Entrega </label>
              <div class="col-xs-12 abajocaja">
                        <div data-min-view="2" data-date-format="yyyy-mm-dd" class="input-group date datetimepicker">
                          <input  id="fechades" size="16" type="text" value="" class="form-control"><span class="input-group-addon btn btn-primary"><i class="icon-th mdi mdi-calendar"></i></span>
                        </div>
              </div>
         </div>
        <div class="form-group">
          <label class="col-sm-12 control-label labelleft" >Dirección de entrega:</label>
          <div class="col-sm-12 abajocaja" >

            {!! Form::select( 'direccion_select', $combodirecciones, array(),
                              [
                                'class'       => 'form-control control' ,
                                'id'          => 'direccion_select',
                                'data-aw'     => '1',
                              ]) !!}
          </div>
      
        </div>
        <div class="form-group">
        <label class="col-sm-12 control-label labelleft" >Tipo de pago:</label>
          <div class="col-sm-12 abajocaja" >

          {!! Form::select( 'tipopago_select', $combotipopago, array(),
                            [
                              'class'       => 'form-control control' ,
                              'id'          => 'tipopago_select',
                              'data-aw'     => '1',
                            ]) !!}
          </div>
        </div>
    </div>


</div>
<script src="{{ asset('public/js/pedido/app-deuda.js') }}" type="text/javascript"></script>

<script src="{{ asset('public/lib/moment.js/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/lib/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>  
<script type="text/javascript">
    $(document).ready(function(){
      //initialize the javascript
      App.init();
      App.formElements();
      App.ChartJs();

    });
</script> 