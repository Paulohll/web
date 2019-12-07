
<div class="col-md-12">
  <div class="panel panel-border-color panel-border-color-success">
    <div class="panel-heading">INFORMACION</div>
    <div class="panel-body">

		<div class="col-sm-6">
			
			<div class="form-group">
				<label  class="col-sm-4 control-label" >
					<div class="tooltipfr">Nombre <span class='requerido'>*</span>
					  <span class="tooltiptext">Nombre del cupón</span>
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

    </div>
  </div>
</div>




<div class="col-md-12">
  <div class="panel panel-border-color panel-border-color-warning">
    <div class="panel-heading">CONDICIONES</div>
    <div class="panel-body">

		<div class="col-sm-6">

			<div class="form-group">
			  	<label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Fecha Inicio <span class='requerido'>*</span>
					  <span class="tooltiptext">Fecha donde se iniciara la aplicación del cupón.</span>
					</div>
			  	</label>
				<div class="col-sm-8">
				    <div 	data-start-view="2"  
				    		data-date-format="dd-mm-yyyy hh:ii" 
				     		class="input-group date datetimepicker">
				      		<input size="16" type="text" 
				      		value="@if(isset($regla)){{old('fechainicio' ,date_format(date_create($regla->fechainicio), 'd-m-Y H:i'))}}@else{{old('fechainicio',$fechaactual)}}@endif"
				      		id='fechainicio' name='fechainicio' required = ""
				      		placeholder="Fecha Inicio" class="form-control input-sm">
				      		<span class="input-group-addon btn btn-primary">
				      			<i class="icon-th mdi mdi-calendar"></i>
				      		</span>

				    </div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
			  	<label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Fecha Fin <span class='requerido'>*</span>
					  <span class="tooltiptext">Fecha donde culminara la aplicación del cupón (si es vacio sera sin fecha fin).</span>
					</div>
				</label>
			  	<div class="col-sm-8">
				    <div 	data-start-view="2" 
				    		data-date-format="dd-mm-yyyy hh:ii" 
				     		class="input-group date datetimepicker">
				      		<input size="16" 
				      		type="text" 
				      		value="@if(isset($regla))@if($regla->fechafin == $fechavacia){{old('fechafin')}}@else{{old('fechafin' ,date_format(date_create($regla->fechafin), 'd-m-Y H:i'))}}@endif @else{{old('fechafin')}}@endif"
				      		id='fechafin' name='fechafin' 
				      		data-parsley-fechamayor='fechainicio'
				      		placeholder="Fecha Fin" class="form-control input-sm">
				      		<span class="input-group-addon btn btn-primary">
				      			<i class="icon-th mdi mdi-calendar"></i>
				      		</span>

				    </div>
			  	</div>
			</div>
		</div>

		<div class="col-sm-6 ind_departamento">
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

		<div class="col-sm-6">
			<div class="form-group">
			  	<label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Cantidad Mínima <span class='requerido'>*</span>
					  <span class="tooltiptext">Cantidad que deberia comprar para aplicación del cupón
					  							(si es 0 aplica en cualquier cantidad comprada).</span>
					</div>
			  	</label>
			  	<div class="col-sm-8 abajocaja">

			    <input  type="text"
			            id="cantidadminima" name='cantidadminima' 
			            value="@if(isset($regla)){{old('cantidadminima' ,$regla->cantidadminima)}}@else{{old('cantidadminima',0)}}@endif"
			            placeholder="Cantidad Mínima"
			            required = "" class="form-control input-sm solonumero" data-parsley-type="number"
			            autocomplete="off" data-aw="6"/>

			    @include('error.erroresvalidate', [ 'id' => $errors->has('cantidadminima')  , 
			                                          'error' => $errors->first('cantidadminima', ':message') , 
			                                          'data' => '6'])

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
			  		<div class="tooltipfr">Negociación <span class='requerido'>*</span>
					  <span class="tooltiptext">Del precio original cuanto lo puede bajar.</span>
					</div>
			  </label>
			  <div class="col-sm-8 abajocaja">
				
                <div class="input-group xs-mb-15">
					<span class="input-group-addon ssoles">S/.</span>
					<span class="input-group-addon sporcentaje">%</span>

				    <input  type="text"
				            id="descuento" name='descuento' 
				            value="@if(isset($regla)){{old('descuento' ,$regla->descuento)}}@else{{old('descuento')}}@endif" 
				            placeholder="Negociación"
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