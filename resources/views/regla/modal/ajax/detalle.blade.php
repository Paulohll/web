<div class="modal-header">
	<div class="panel-heading">
		{{$cliente->NOM_EMPR}} , {{$producto->NOM_PRODUCTO}}
		<span class="panel-subtitle">{{$nombre}}</span>
	</div>
</div>
<div class="modal-body">

<div  class="row regla-modal"             
      data_producto='{{$producto->COD_PRODUCTO}}'
      data_cliente='{{$cliente->id}}'
      data_contrato='{{$cliente->COD_CONTRATO}}'
      data_prefijo='{{$prefijo}}'
      data_color='{{$color}}'>
    <div class="col-md-6">
      	<p class="text-left">Reglas.</p>

       <div class="col-xs-12 margen-top-filtro">
            <div class="form-group">
              <div class="col-sm-12 abajocaja" >

                <div class="input-group my-group"> 
                      {!! Form::select( 'regla_id', $comboreglas, array(),
                                        [
                                          'class'       => 'form-control control select2' ,
                                          'id'          => 'regla_id',
                                          'data-aw'     => '1',
                                        ]) !!}
                      <span class="input-group-btn">
                        <button class="btn btn-{{$color}} btn-regla-modal"
                                type="submit"><span class="mdi mdi-long-arrow-tab"></span></button>
                      </span>
                </div>
              </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">
      <p class="text-left">Lista de {{$nombreselect}}. (5 ultimas)</p>

          <div class='etiques-modal-reglas etm{{$prefijo}}{{$producto->COD_PRODUCTO}}{{$cliente->id}}'>
              @include('regla.modal.ajax.etiquetas')
          </div>

    </div>

</div>

</div>
<script>
	$('#regla_id').select2();
</script>