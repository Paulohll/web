<div class="modal-header">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title"><strong>{{$pedido->empresa->NOM_EMPR}}</strong></h3>
  <h5 class="modal-title">{{$pedido->empresa->NRO_DOCUMENTO}} / {{$funcion->funciones->cuenta_cliente($pedido->cliente_id)}}</h5>
  <h5 class="modal-title"> Dirección entrega : {{$pedido->direccionentrega->NOM_DIRECCION}}</h5>
  <h5 class="modal-title"> Tipo de Pago : {{$funcion->funciones->data_categoria($pedido->tipopago_id)->NOM_CATEGORIA}}</h5>
  <h5 class="modal-title"> Glosa : {{$pedido->glosa}}</h5>
  <input type="hidden" name="id_pedido_modal" id="id_pedido_modal" value="{{$pedido_id}}">

</div>
<div class="modal-body">
  <div class="scroll_text">


  	<table class="table listatabledetalle">
	    <thead>
	      <tr>
	      	<th>
	      	</th>
		    <th>Cantidad</th>
	        <th>Producto</th>
	        <th>Estado</th>
	        <th>Precio</th>
	        <th>Importe</th>
	        <th>Empresa Recepción</th>
	      </tr>
	    </thead>
	    <tbody>

	   @foreach($detalle_pedido as $item)

            @php
              	$key              		=   array_search($item->id, array_column($array_detalle_pedido, 'detalle_pedido_id'));
              	$checked       			=   $array_detalle_pedido[$key]->checked;
              	$empresa_id       		=   $array_detalle_pedido[$key]->empresa_id;
			  	$estado_id        		=   $array_detalle_pedido[$key]->estado_id;

			  	$disabled         		=   '';
			  	if($checked == ''){$disabled  =   'disabled';}

			  	$empresa_receptora_id 	=   $empresa_id;
			  	if(trim($item->empresa_receptora_id) != ''){$empresa_receptora_id  =   $item->empresa_receptora_id;}


            @endphp



	      	<tr class='detalle_pedido'
	      		data_detalle_pedido_id = '{{$item->id}}'
	      		data_estado_id = '{{$item->estado_id}}'
	      		>
		        <td>  

		          <div class="text-center be-checkbox be-checkbox-sm">
		            <input  type="checkbox"
		                    class="{{Hashids::encode(substr($item->id, -8))}}p" 
		                    id="{{Hashids::encode(substr($item->id, -8))}}p" 
		                    {{$checked}}
		                    @if($estado_id != 'EPP0000000000003') disabled @endif>

		            <label  for="{{Hashids::encode(substr($item->id, -8))}}p"
		                  data-atr = "ver"
		                  class = "checkbox"                    
		                  name="{{Hashids::encode(substr($item->id, -8))}}p"
		            ></label>
		          </div>
		        </td>

		        <td>{{$item->cantidad}}</td>
		        <td>{{$item->producto->NOM_PRODUCTO}}</td>

		        <td>

		            @if($item->estado_id == 'EPP0000000000003') 
		              <span class="badge badge-warning">{{$item->NOM_CATEGORIA}}</span> 
		            @else
			            @if($item->estado_id == 'EPP0000000000004') 
			              <span class="badge badge-success">{{$item->NOM_CATEGORIA}}</span> 
			            @else
			                <span class="badge badge-danger">{{$item->NOM_CATEGORIA}}</span>
			            @endif

		            @endif
		        </td>


		        <td>{{$item->precio}}</td>
		        <td>{{$item->total}}</td>
		        <td width="350px" class='select_empresa'>
		        	<div class="form-group">
                        <div class="col-sm-12 abajocaja" >

                          {!! Form::select( 'empresa_id', $comboempresas, array($empresa_receptora_id),
                                            [
                                              'class'       => 'select2 form-control control input-sm' ,
                                              'id'          => 'empresa_id',
                                              'required'    => '',
                                              $disabled,
                                              'data-aw'     => '1',
                                            ]) !!}

                        </div>
                    </div>
		        </td>
	      	</tr>                    
	    @endforeach

	    </tbody>
	    <tfooter>
	      <tr>
		    <th colspan="5"></th>
	        <th>{{$pedido->total}}</th>
	        <th></th>
	      </tr>
	    </tfooter>
  	</table>
  </div>

</div>
<div class="modal-footer">
  <button type="button" data-dismiss="modal" class="btn btn-success btn_guardar_detalle">Seleccionar</button>
</div>