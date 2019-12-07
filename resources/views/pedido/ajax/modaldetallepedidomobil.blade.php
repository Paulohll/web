<div class="modal-header">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title"><strong>{{$pedido->empresa->NOM_EMPR}}</strong></h3>
  <h5 class="modal-title">{{$pedido->empresa->NRO_DOCUMENTO}} / {{$funcion->funciones->cuenta_cliente($pedido->cliente_id)}}</h5>

  <h5 class="modal-title"> Dirección entrega : {{$pedido->direccionentrega->NOM_DIRECCION}}</h5>
  <h5 class="modal-title"> Glosa : {{$pedido->glosa}}</h5>
</div>
<div class="modal-body">

  	<div class="scroll_text">
		
	   	@foreach($pedido->detallepedido as $item)

			<div class='col-sm-12 productoseleccion col-mobil-top'>
			     <div class='panel panel panel-contrast'>
			         <div class='panel-heading cell-detail'>
							{{$item->producto->NOM_PRODUCTO}}
			             	<span class='panel-subtitle cell-detail-producto'>Cantidad : {{$item->cantidad}}</span>
			             	<span class='panel-subtitle cell-detail-producto'>Precio : {{$item->precio}} </span>
			             	<span class='panel-subtitle cell-detail-producto'><strong> Importe : {{$item->total}} </strong></span>

			             	<span class='panel-subtitle cell-detail-producto'>
			             			Estado : 
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
								            @if($item->estado_id == 'EPP0000000000004') 
								              <span class="badge badge-success">
								              	{{$funcion->funciones->data_categoria($item->estado_id)->NOM_CATEGORIA}}
								              </span> 
								            @else
								                <span class="badge badge-primary">GENERADO</span>
								            @endif
							            @endif
						            @endif
			             	</span>


			             	<span class='panel-subtitle cell-detail-producto'>
			             			Empresa despacho : 

						            @if(is_null($item->empresa_receptora_id) or $item->empresa_receptora_id == '')
						              	{{$funcion->funciones->data_empresa($item->empresa_id)->NOM_EMPR}}
						            @else
						                {{$funcion->funciones->data_empresa($item->empresa_receptora_id)->NOM_EMPR}}
						            @endif

	
			             	</span>


			         </div>
			     </div>
			</div>
	    @endforeach

	</div>




</div>
<div class="modal-footer">
  <span class='panel-subtitle cell-detail-producto'><strong> TOTAL : {{$pedido->total}} </strong></span>	
  <button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
</div>