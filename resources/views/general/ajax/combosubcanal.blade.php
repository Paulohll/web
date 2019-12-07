<div class="form-group">
	<label class="col-sm-12 control-label labelleft" >Sub Canal :</label>
	<div class="col-sm-12 abajocaja" >
	  {!! Form::select( 'subcanal_id', $combo_sub_canal, array(),
	                    [
	                      'class'       => 'select2 form-control control input-sm' ,
	                      'id'          => 'subcanal_id',
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