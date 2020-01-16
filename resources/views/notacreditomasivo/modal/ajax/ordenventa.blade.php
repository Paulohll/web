
<div class="panel-heading">Lista de ordenes de venta ({{$funcion->funciones->data_cliente($cuenta_id)->NOM_EMPR}})
  <div class="tools tooltiptop">
    <a href="#" class="tooltipcss opciones" id='buscarordenventa_modal'>
      <span class="tooltiptext">Buscar Orden Venta</span>
      <span class="icon mdi mdi-search"></span>
    </a>

    <input type="hidden" name="cuenta_id_m_ov" id='cuenta_id_m_ov' value = {{$cuenta_id}}>

  </div>
</div>


<div class="modal-header" style = "padding: 0px !important;">
	<div class="panel-heading">

    <div class='filtrotabla row'>
      <div class="col-xs-12">
        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
            <div class="form-group">
              <label class="col-sm-12 control-label">
                Fecha Inicio
              </label>
              <div class="col-sm-12">
                <div data-min-view="2" data-date-format="dd-mm-yyyy" class="input-group date datetimepicker">
                          <input size="16" type="text" value="{{$fecha_inicio}}" id='finicio' name='finicio' class="form-control input-sm">
                          <span class="input-group-addon btn btn-primary"><i class="icon-th mdi mdi-calendar"></i></span>
                </div>
              </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
            <div class="form-group">
              <label class="col-sm-12 control-label">
                Fecha Fin
              </label>
              <div class="col-sm-12">
                <div data-min-view="2" data-date-format="dd-mm-yyyy"  class="input-group date datetimepicker">
                          <input size="16" type="text" value="{{$fecha_fin}}" id='ffin' name='ffin' class="form-control input-sm">
                          <span class="input-group-addon btn btn-primary"><i class="icon-th mdi mdi-calendar"></i></span>
                </div>
              </div>
            </div>
        </div>
      </div>

    </div>

	</div>
</div>


<div class="modal-body" style="padding: 10px 20px 20px;">
  <div class="scroll_text ajax_lista_orden_venta">
      @include('notacreditomasivo.modal.ajax.listaordenventa')
  </div>
</div>

