
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

		<div class="col-sm-6 ">

			<div class="form-group">
				<label class="col-sm-4 control-label">
					<div class="tooltipfr">Descripción <span class='requerido'>*</span>
					  <span class="tooltiptext">Descripción del cupón. Nunca se mostrará al cliente.</span>
					</div>			
				</label>
				<div class="col-sm-8">

				    <input  type="text"
				            id="descripcion" name='descripcion' 
				            value="@if(isset($regla)){{old('descripcion' ,$regla->descripcion)}}@else{{old('descripcion')}}@endif"
				            placeholder="Descripción"
				            required = ""
				            maxlength="400"
				            autocomplete="off" class="form-control input-sm" data-aw="2"/>

				      		@include('error.erroresvalidate', [ 'id' => $errors->has('descripcion')  , 
				                                          'error' => $errors->first('descripcion', ':message') , 
				                                          'data' => '2'])


				</div>
			</div>

		</div>

		<div class="col-sm-6">

			<div class="form-group">
				<label class="col-sm-4 control-label">
					<div class="tooltipfr">Cupón <span class='requerido'>*</span>
					  <span class="tooltiptext">Este es el código que se debe introducir para aplicar los cupones descuento.</span>
					</div>	
				</label>
				<div class="col-sm-8">
			        <div class="input-group">
					    <input  type="text"
					            id="cupon" name='cupon' 
					            value="@if(isset($regla)){{old('cupon' ,$regla->cupon)}}@else{{old('cupon')}}@endif" 
					            placeholder="Cupón"
					            required = ""
					      		maxlength="50"
					            autocomplete="off" class="form-control input-sm" data-aw="3"/>
						<span class="input-group-btn">
			                <button type="button" class="generarcupon btn btn-success input-sm">
			                	<i class="mdi mdi-swap-vertical"></i> Generar
			                </button>
			            </span>       
					      		@include('error.erroresvalidate', [ 'id' => $errors->has('cupon')  , 
					                                          'error' => $errors->first('cupon', ':message') , 
					                                          'data' => '3'])
			        </div>
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





		<div class="col-sm-6">
			<div class="form-group">
			  <label class="col-sm-4 control-label">
			  	
			  		<div class="tooltipfr">Total disponible <span class='requerido'>*</span>
					  <span class="tooltiptext">Total de cupones disponibles para ser utilizados
					  							(si es 0 el total de cupones son ilimitados).</span>
					</div>
			  </label>
			  <div class="col-sm-8 abajocaja">

			    <input  type="text"
			            id="totaldisponible" name='totaldisponible' 
			            value="@if(isset($regla)){{old('totaldisponible' ,$regla->totaldisponible)}}@else{{old('totaldisponible',0)}}@endif" placeholder="Total disponible"
			            required = "" class="form-control input-sm solonumero" data-parsley-type="number"
			            autocomplete="off" data-aw="7"/>

			    @include('error.erroresvalidate', [ 'id' => $errors->has('totaldisponible')  , 
			                                          'error' => $errors->first('totaldisponible', ':message') , 
			                                          'data' => '7'])

			  </div>
			</div>
		</div>

		<div class="col-sm-6">

			<div class="form-group">
			 	 <label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Total disponible para cada usuario <span class='requerido'>*</span>
					  <span class="tooltiptext">Total de cupones disponibles para ser utilizados por cliente
					  							(si es 0 puede utilizar los cupones que desee).</span>
					</div>
				</label>
			  <div class="col-sm-8 abajocaja">

			    <input  type="text"
			            id="totalcadacuenta" name='totalcadacuenta' 
			            value="@if(isset($regla)){{old('totalcadacuenta' ,$regla->totalcadacuenta)}}@else{{old('totalcadacuenta',0)}}@endif" 
			            placeholder="Total disponible para cada usuario"
			            required = "" class="form-control input-sm solonumero" data-parsley-type="number"
			            autocomplete="off" data-aw="8"/>

			    @include('error.erroresvalidate', [ 'id' => $errors->has('totalcadacuenta')  , 
			                                          'error' => $errors->first('totalcadacuenta', ':message') , 
			                                          'data' => '8'])

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
			  <label class="col-sm-4 control-label">Aplicar un descuento</label>
			  <div class="col-sm-8">

			    <div class="be-radio inline">
			      <input type="radio"  
			      name="tipodescuento" id="importe" class='tipodescuento' value='IMP'
			      @if(isset($regla)) @if($regla->tipodescuento == 'IMP') checked @endif @else checked @endif >
			      <label for="importe">Importe</label>
			    </div>

			    <div class="be-radio inline">
			      <input type="radio" name="tipodescuento" id="porcentaje" class='tipodescuento' value='POR'
			      @if(isset($regla)) @if($regla->tipodescuento == 'POR') checked @endif @endif>
			      <label for="porcentaje">Porcentaje </label>
			    </div>

			  </div>
			</div>
		</div>

		<div class="col-sm-6">

			<div class="form-group">
			  <label class="col-sm-4 control-label">
			  		<div class="tooltipfr">Importe/Porcentaje <span class='requerido'>*</span>
					  <span class="tooltiptext">El descuento que se aplicara al cupón.</span>
					</div>
			  </label>
			  <div class="col-sm-8 abajocaja">
				
                <div class="input-group xs-mb-15">
					<span class="input-group-addon ssoles">S/.</span>
					<span class="input-group-addon sporcentaje">%</span>

				    <input  type="text"
				            id="descuento" name='descuento' 
				            value="@if(isset($regla)){{old('descuento' ,$regla->descuento)}}@else{{old('descuento')}}@endif" 
				            placeholder="Importe / Porcentaje"
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