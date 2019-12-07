<div class='row reporte'>
  
  <table id="tablereporte" class="table table-striped table-hover table-fw-widget">
    <thead>
      <tr>
        <th class= 'center tabladp' >DATOS</th>        
        <th class= 'center tablaho' colspan='3'>PRECIOS ({{$fechafin}})</th> 
        <th class= 'center tablaho' >PROMOCION</th>       
      </tr>

      <tr>
        <th class= 'tabladp'>PRODUCTO</th>             
        <th class= 'center tablaho'>MPSA</th> 
        <th class= 'center tablaho'>OML</th>
        <th class= 'center tablaho'>DIST</th>
        <th class= 'center tablaho' >Reglas</th>
      </tr>
    </thead>
    <tbody>

      @foreach($listadeproductos as $index => $item) 
		        <tr>

                <td>{{$item->NOM_PRODUCTO}}</td>

                @php
                  $precio_regular_mpsa          =   0.0000;
                  $precio_regular_oml           =   0.0000;
                  $precio_regular_dist          =   0.0000;

                  $reglas                       =   '';
                  $precio_regular_mpsa          =   $funcion->funciones->calculo_precio_regular_fecha_subcanal('SCV0000000000004',$item,$fechafin);
                  $precio_regular_oml           =   $funcion->funciones->calculo_precio_regular_fecha_subcanal('SCV0000000000020',$item,$fechafin);
                  $precio_regular_dist          =   $funcion->funciones->calculo_precio_regular_fecha_subcanal('SCV0000000000005',$item,$fechafin);
                  $reglas                       =   $funcion->funciones->reglas_producto_fecha_sub_canales($item->producto_id,$fechafin);


                @endphp


                <td class='left negrita columna_marcada1'>
                      S/. {{$precio_regular_mpsa}}
                </td>

                <td class='left negrita columna_marcada1'>
                      S/. {{$precio_regular_oml}}
                </td>

                <td class='left negrita columna_marcada1'>
                      S/. {{$precio_regular_dist}}

                </td>
                <td class='left negrita'>
                      {{$reglas}}
                </td>



		        </tr>
      @endforeach       

    </tbody>
  </table>

</div>

<script type="text/javascript">
  $(document).ready(function(){
     App.dataTables();
  });
</script> 