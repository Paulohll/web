
<div class="col-md-12">
  <div class="panel panel-border-color panel-border-color-success">
    <div class="panel-heading">INFORMACION</div>
    <div class="panel-body">

		<div class="col-sm-6">
			
			<div class="form-group">
				<label  class="col-sm-4 control-label" >
					<div class="tooltipfr">Nombre <span class='requerido'>*</span>
					  <span class="tooltiptext">Nombre de la regla</span>
					</div>
				</label>
				<div class="col-sm-8">

				    <input  type="text"
				            id="nombre" name='nombre' 
				            value="@if(isset($regla)){{old('nombre' ,$regla->nombre)}}@else{{old('nombre')}}@endif"
				            placeholder="Nombre"
				            required = ""
				            maxlength="100"
				            autocomplete="off" class="form-control input-sm" data-aw="1"/>

				      		@include('error.erroresvalidate', [ 'id' => $errors->has('nombre')  , 
				                                          'error' => $errors->first('nombre', ':message') , 
				                                          'data' => '1'])

				</div>
			</div>


		</div>

		<div class="col-sm-6">
			<div class="form-group">

			  	<label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Departamento
			  			<span class="tooltiptext">Seleccione al departamento al cual se afectara el precio regular.</span>
					</div>
				</label>
				
				<div class="col-sm-8 ">
			          {!! Form::select( 'departamento', $combodepartamentos, array(),
                      [
                        'class'       => 'form-control control select2' ,
                        'id'          => 'departamento',
                        'data-aw'     => '1',
                      ]) !!}
				</div>

			</div>	
		</div>



    </div>
  </div>
</div>


<div class="col-md-12">
  <div class="panel panel-border-color panel-border-color-danger">
    <div class="panel-heading">ACCIONES</div>
    <div class="panel-body">


		<div class="col-sm-6">



			<div class="form-group">
			  <label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Precio regular <span class='requerido'>*</span>
					  <span class="tooltiptext">El monto sera el nuevo precio regular del producto.</span>
					</div>
			  </label>
			  <div class="col-sm-8 abajocaja">
				
                <div class="input-group xs-mb-15">
					<span class="input-group-addon ssoles">S/.</span>
				    <input  type="text"
				            id="descuento" name='descuento' 
				            value="@if(isset($regla)){{old('descuento' ,$regla->descuento)}}@else{{old('descuento')}}@endif" 
				            placeholder="Precio regular"
				            required = "" class="form-control input-sm importe" data-parsley-type="number"
				            autocomplete="off" data-aw="6"/>
                </div>


			    @include('error.erroresvalidate', [ 'id' => $errors->has('descuento')  , 
			                                          'error' => $errors->first('descuento', ':message') , 
			                                          'data' => '6'])

			  </div>
			</div>

		</div>



    </div>
  </div>
</div>


<div class="row xs-pt-15">
<div class="col-xs-6">
    <div class="be-checkbox">

    </div>
</div>
<div class="col-xs-6">
  <p class="text-right">
    <button type="submit" class="btn btn-space btn-primary">Guardar</button>
  </p>
</div>
</div>