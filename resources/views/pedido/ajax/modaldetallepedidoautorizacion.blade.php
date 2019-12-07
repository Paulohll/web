<div class="modal-header">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title"><strong>{{$pedido->empresa->NOM_EMPR}}</strong></h3>
  <h5 class="modal-title">{{$pedido->empresa->NRO_DOCUMENTO}} / {{$funcion->funciones->cuenta_cliente($pedido->cliente_id)}}</h5>

  <h5 class="modal-title"> Dirección entrega : {{$pedido->direccionentrega->NOM_DIRECCION}}</h5>
  <h5 class="modal-title"> Glosa : {{$pedido->glosa}}</h5>
</div>
<div class="modal-body">

  	<table class="table lista_detalle_pedido">
	    <thead>
	      <tr>
	        <th>Producto</th>
	        <th>Estado</th>
		    <th>Cantidad</th>
	        <th>Precio</th>
	        <th>Importe</th>
      		<th>Rechazar</th>
	      </tr>
	    </thead>
	    <tbody>

	   @foreach($pedido->detallepedido as $item)
		      <tr 	class= 'fila_producto'
		      		data_detallepedido = "{{$item->id}}" 
		      		data_pedido = "{{$item->pedido_id}}"
		      		>
		        <td>{{$item->producto->NOM_PRODUCTO}}</td>

		        <td class='estado_detalle_pedido'>

		            @if($item->estado_id == 'EPP0000000000003') 
		              	<span class="badge badge-warning"> 
		              		{{$funcion->funciones->data_categoria($item->estado_id)->NOM_CATEGORIA}}
						</span> 
		            @else
			            @if($item->estado_id == 'EPP0000000000005') 
			              <span class="badge badge-danger">
			              	{{$funcion->funciones->data_categoria($item->estado_id)->NOM_CATEGORIA}}
			              </span> 
			            @else
			                <span class="badge badge-primary">GENERADO</span>
			            @endif
		            @endif
		        </td>


		        <td class= 'columna-cantidad'>

		            <input type="text"  
		                   id="cantidad" 
		                   name="cantidad"
		                   value='{{$item->cantidad}}'
		                   class="form-control input-sm dinero updatecantidad"
		                   >

		        </td>
		        <td class= 'columna-precio'>

		            <input type="text"  
		                   id="precio" 
		                   name="precio"
		                   value='{{$item->precio}}'
		                   class="form-control input-sm dinero updateprice"
		                   >

		        	
		        </td>
		        <td class= 'columna-importe'>
		        	{{$item->total}}
		        </td>

		        <td class = 'rechazar'>
		          <span class="badge badge-danger btn-eyes btn-detalle-pedido-rechazar" 
		                data-id="{{$item->id}}">
		            <span style='color:#fff' class="mdi mdi-close md-trigger"></span>
		          </span>
		        </td>

		      </tr>                    
	    @endforeach

	    </tbody>
	    <tfooter>
	      <tr>
		    <th colspan="3"></th>
	        <th class='p{{$pedido->id}}'>{{$pedido->total}}</th>
	      </tr>
	    </tfooter>
  	</table>


</div>
<div class="modal-footer">
  <button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
</div>

<script>

  $('.dinero').inputmask({ 'alias': 'numeric', 
  'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
  'digitsOptional': false, 
  'prefix': '', 
  'placeholder': '0'});

</script>