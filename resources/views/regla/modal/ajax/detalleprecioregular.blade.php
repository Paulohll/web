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


    <div class="col-sm-6">

      <div class="form-group">
          <label class="col-sm-12 control-label">
            <div class="tooltipfr">Departamento
              <span class="tooltiptext">Seleccione al departamento al cual se afectara el precio regular.</span>
          </div>
        </label>

        <div class="col-sm-12 ">
                {!! Form::select( 'departamento_id_pr', $combodepartamentos, array(),
                      [
                        'class'       => 'form-control control select2' ,
                        'id'          => 'departamento_id_pr',
                        'data-aw'     => '1',
                      ]) !!}
        </div>
      </div> 



      <div class="form-group">
        <label class="col-sm-12 control-label">
            <div class="tooltipfr">Precio regular <span class='requerido'>*</span>
            <span class="tooltiptext">El monto sera el nuevo precio regular del producto.</span>
          </div>
        </label>
        <div class="col-sm-12 abajocaja">
        
                <div class="input-group xs-mb-15">
          <span class="input-group-addon ssoles">S/.</span>
            <input  type="text"
                    id="descuento_pr" name='descuento_pr' 
                    value="" 
                    placeholder="Precio regular"
                    required = "" class="form-control input-sm importe" data-parsley-type="number"
                    autocomplete="off" data-aw="6"/>
                </div>

        </div>
      </div>

      <div class="col-xs-12">
        <p class="text-right">
          <button type="button" class="btn btn-space btn-primary btn_regla_asignar_crear">Guardar</button>
        </p>
      </div>

    </div>




    <div class="col-md-6">
      <p class="text-left">Lista de {{$nombreselect}}.</p>

          <div class='etiques-modal-reglas etm{{$prefijo}}{{$producto->COD_PRODUCTO}}{{$cliente->id}}'>
              @include('regla.modal.ajax.precioregular')
          </div>

    </div>

</div>

</div>
<script>
	$('#departamento_id_pr').select2();

  $('.importe').inputmask({ 'alias': 'numeric', 
  'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
  'digitsOptional': false, 
  'prefix': '', 
  'placeholder': '0'});

</script>