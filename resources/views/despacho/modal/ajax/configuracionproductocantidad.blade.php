<div class="modal-header">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title modal-titulo-configuracion">{{$producto->NOM_PRODUCTO}}  ({{$unidad_medida->NOM_CATEGORIA}})</h3>
</div>
<div class="modal-body">
    <div class="panel-body">
      <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2  col-lg-8 col-lg-offset-2">
        <div class="form-group">
          <label class="col-sm-12 control-label">
            Cantidad de bolsas que contiene un <b>SACO</b>
          </label>
          <div class="col-sm-12">

              <input  type="text"
                      id="cantidad_bolsa_saco" 
                      name='cantidad_bolsa_saco' 
                      value="{{$producto->CAN_BOLSA_SACO}}" 
                      placeholder="Cantidad bolsa saco"
                      required = "" class="form-control input-sm importe" data-parsley-type="number"
                      autocomplete="off" data-aw="1"/>

          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2  col-lg-8 col-lg-offset-2">
        <div class="form-group">
          <label class="col-sm-12 control-label">
            Cantidad de sacos que contiene un <b>PALET</b>
          </label>
          <div class="col-sm-12">

              <input  type="text"
                      id="cantidad_saco_palet" 
                      name='cantidad_saco_palet' 
                      value="{{$producto->CAN_SACO_PALET}}" 
                      placeholder="Cantidad saco palet"
                      required = "" class="form-control input-sm importe" data-parsley-type="number"
                      autocomplete="off" data-aw="2"/>

          </div>
        </div>
      </div>
    </div>

</div>
<div class="modal-footer">
	<input type="hidden" name="producto_configuracion_id" id="producto_configuracion_id" value='{{$producto->COD_PRODUCTO}}'>
  	<button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
  	<button type="button" data-dismiss="modal" class="btn btn-success" id="modificarconfiguracionprouducto">Modificar</button>
</div>

<script type="text/javascript">
    $('.importe').inputmask({ 'alias': 'numeric', 
    'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
    'digitsOptional': false, 
    'prefix': '', 
    'placeholder': '0'});
</script> 