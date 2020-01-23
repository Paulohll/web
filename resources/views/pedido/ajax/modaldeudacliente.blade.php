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
  	<table class="table">
	    <thead>
	      <tr>
	        <th>Documento</th>
	        <th>Fecha</th>
	        <th>Saldo</th>
	        <th>Plazo de deuda</th>
	        <th>DIAS</th> 
	      </tr>
	    </thead>
	    <tbody>

	    @while ($row = $lista_deuda_cliente->fetch())

	        @if($row['TIPDOC'] == 'DOCUMENTO INTERNO VENTAS') 
			    <tr>
			        <td class="cell-detail">
		              <span><b>DIV : </b>{{$row['NUMDOC']}}</span>
		              <span><b>DOCUMENTO CTBL : </b>{{$row['COD_DOCUMENTO_CTBLE']}}</span>
		            </td>

		            <td class="cell-detail">
		              <span><b>Emision : </b>{{date_format(date_create($row['FEC_EMISION']), 'd-m-Y')}}</span>
		              <span><b>Vencimiento : </b>{{date_format(date_create($row['FEC_VENCIMIENTO']), 'd-m-Y')}}</span>
		              <span><b>Tipo de pago : </b>{{$funcion->funciones->data_documento_ctbl($row['COD_DOCUMENTO_CTBLE'])->TXT_CATEGORIA_TIPO_PAGO}}</span>
		            </td>


			        <td>{{number_format($row['CAN_SALDO'], 2, '.', ',')}}</td>
			        <td class= 'center'>
			        	<span class="badge badge-primary btn-eyes">
			            	{{$row['PLAZO_DEUDA']}}
			          	</span>
			        	
			    	</td>
		            <td class="cell-detail">
		              <span><b>DE 0 A 30 DIAS : </b>{{number_format($row['PD030'], 2, '.', ',')}}</span>
		              <span><b>DE 31 A 90 DIAS : </b>{{number_format($row['PD3190'], 2, '.', ',')}}</span>
		              <span><b>DE 91 A 180 DIAS : </b>{{number_format($row['PD91180'], 2, '.', ',')}}</span>
		              <span><b>DE 181 A MAS DIAS : </b>{{number_format($row['PD181M'], 2, '.', ',')}}</span>
			    	</td>

			    </tr> 
		   	@endif
	      @endwhile

	    </tbody>
  	</table>
  </div>

</div>
<div class="modal-footer">
  <button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
</div>