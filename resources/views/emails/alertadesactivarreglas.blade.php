<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <style type="text/css">
        	section{
        		width: 100%;
        		background: #E8E8E8;
        		padding: 0px;
        		margin: 0px;
        	}

        	.panelcontainer{
        		width: 50%;
        		background: #fff;
        		margin: 0 auto;


        	}
        	.panelhead{
        		background: #eb6357;
        		padding-top: 10px;
        		padding-bottom: 10px;
        		color: #fff;
        		text-align: center;
        		font-size: 1.2em;
        	}
        	.panelbody,.panelbodycodigo{
        		padding-left: 15px;
        		padding-right: 15px;
        	}
            .panelbodycodigo h3 small{
                color: #08257C;
            }

            table, td, th {    
                border: 1px solid #ddd;
                text-align: left;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                padding: 15px;
                font-size: 12px;
            }

        </style>

    </head>


    <body>
    	<section>
    		<div class='panelcontainer'>
    			<div class="panel">
                    <div class='panelbodycodigo'>
                        	<h3> {{$titulo}}  </h3>
                    </div>
    				<div class="panelhead">Lista de Reglas</div>
    				<div class='panelbody'>
                            <table  class="table demo" >
                                <tr>
                                    <th>
                                        Codigo
                                    </th>
                                    <th>
                                        Nombre
                                    </th>
                                    <th>
                                        Tipo Regla
                                    </th>
                                    <th>
                                        Monto
                                    </th>
                                    <th>
                                        Fecha Fin
                                    </th>
                                    <th>
                                        Empresa
                                    </th>
                                    <th>
                                        Centro
                                    </th>                                        
                                </tr>
                                @foreach($lista_reglas as $posicion=>$regla)
                                <tr>

	                                    <td>{{$regla['codigo']}}</td>
	                                    <td>{{$regla['nombre']}}</td>
	                                    <td>
				                            @if($regla['tiporegla'] == 'POV') 
				                            	Promocion ó Descuento
				                            @else 
					                            @if($regla['tiporegla'] == 'PNC') 
					                            	Promocion ó Descuento
					                            @else 
						                            @if($regla['tiporegla'] == 'CUP') 
						                            	CUPON
						                            @else 
						                            	NEGOCIACION
						                            @endif
					                            @endif
				                            @endif
	                                	</td>
				                        <td>
				                            @if($regla['tipodescuento'] == 'POR') 
				                              %
				                            @else 
				                              S/.
				                            @endif
				                            {{number_format($regla['descuento'], 4, '.', ',')}}
				                        </td>
	                                    <td>{{date_format(date_create($regla['fechafin']), 'd-m-Y H:i')}}</td>
	                                    <td>{{$regla['NOM_EMPR']}}</td>
	                                    <td>{{$regla['NOM_CENTRO']}}</td>



                                </tr>
								@endforeach
                            </table>
    				</div>
    			</div>
    		</div>
		</section>
    </body>

</html>


