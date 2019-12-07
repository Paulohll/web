<div class="form-group">
	<label class="col-sm-12 control-label labelleft" >Canal :</label>
	<div class="col-sm-12 abajocaja" >
	  {!! Form::select( 'canal_id', $combo_canal, array(),
	                    [
	                      'class'       => 'select2 form-control control input-sm' ,
	                      'id'          => 'canal_id',
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