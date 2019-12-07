<div class="form-group">
	<label class="col-sm-12 control-label labelleft" >Cliente :</label>
	<div class="col-sm-12 abajocaja" >
	  {!! Form::select( 'cliente_id', $combo_cliente, array(),
	                    [
	                      'class'       => 'select2 form-control control input-sm' ,
	                      'id'          => 'cliente_id',
	                      'required'    => '',
	                      'data-aw'     => '1',
	                    ]) !!}
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	  App.formElements();
	});
</script> 