<div class="form-group">
	<label class="col-sm-12 control-label labelleft" >Reglas :</label>
	<div class="col-sm-12 abajocaja" >
	  {!! Form::select( 'regla_id', $comboreglas, array(),
	                    [
	                      'class'       => 'select2 form-control control input-sm' ,
	                      'id'          => 'regla_id',
	                      'required'    => '',
	                      'data-aw'     => '1',
	                      'multiple' 	=> '',
	                    ]) !!}
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	  App.formElements();
	});
</script> 