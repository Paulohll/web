<div class='row reporte'>
  
  <table id="tablereporte" class="table table-striped table-hover table-fw-widget">
    <thead>
      <tr>
        <th class= 'center tabladp' colspan='3'>DATOS</th>        
        <th class= 'center tablaho' colspan='3'>PRECIOS ({{$fechafin}})</th> 
        <th class= 'center tablaho' >VENTA</th>       
      </tr>

      <tr>
        <th class= 'tabladp'>ID</th>        
        <th class= 'tabladp'>CLIENTE</th>
        <th class= 'tabladp'>PRODUCTO</th>             
        <th class= 'center tablaho'>
          PRECIO REGULAR 
        </th> 
        <th class= 'center tablaho'>DESCUENTO</th>
        <th class= 'center tablaho'>PRECIO TOTAL</th> 
        <th class= 'center tablaho'>ORDEN CEN</th> 
      </tr>
    </thead>
    <tbody>
	  	@foreach($listacliente as $index_c => $item_c)
            @foreach($listadeproductos as $index => $item) 
		        <tr>
		        	  <td class='negrita'>{{$index + 1}}</td>
		            <td>{{$item_c->NOM_EMPR}}</td>
                <td>{{$item->NOM_PRODUCTO}}</td>


                @php
                  $precio_regular    =   0.0000;
                  $descuento         =   0.0000;
                  $ordencen          =   0.0000;


                  $precio_regular    =   $funcion->funciones->calculo_precio_regular_fecha($item_c,$item,$fechafin);
                  $descuento         =   $funcion->funciones->descuento_reglas_producto_fecha($item_c->COD_CONTRATO,$item->producto_id,$item_c->id,'',$fechafin);
                  $precio_descuento  =   (float)$precio_regular - (float)$descuento;


                  $ordencen         =    $funcion->funciones->calculo_precio_venta($item_c,$item,$fechafin);
                @endphp


                <td class='left negrita columna_marcada1'>
                      S/. {{$precio_regular}}
                </td>

                <td class='left negrita'>
                      S/. {{$descuento}}
                </td>

                <td class='left negrita columna_marcada1'>
                      S/. {{$precio_descuento}}

                </td>
                <td class='left negrita'>
                  @if($ordencen > 0) 
                    S/. {{$ordencen}} 
                  @else
                    - 
                  @endif       
                </td>



		        </tr>
            @endforeach       
	  	@endforeach
    </tbody>
  </table>

</div>

<script type="text/javascript">
  $(document).ready(function(){
     App.dataTables();
  });
</script> 