
<div class="modal-header" style = "padding: 12px !important;">
  <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
  <h3 class="modal-title">
    <strong>
      Pedido ({{$ordendespacho->codigo}})
    </strong>
  </h3>
</div>

<input type="hidden" name="tabestado" id='tabestado' value='prod'>

<div class="modal-body modal-pedido-poc" style = "padding: 0px !important;">
  <div class="scroll_text scroll_text_heigth_poc" style = "padding: 0px !important;"> 
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="tab-container">
          <ul class="nav nav-tabs">

            <li class="seltab active" data_tab='prod'>
              <a href="#producto" data-toggle="tab">Productos</a>
            </li>
          </ul>
          <div class="tab-content" style = "padding: 0px !important;">

            <div id="producto" class="tab-pane active cont">
              @include('despacho.modal.ajax.listaatenderproductos')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal-footer">
  <button type="button" data-dismiss="modal" class="btn btn-default modal-close">Cancelar</button>
  <button type="submit" data-dismiss="modal" class="btn btn-success" id="agregarproductosatender">Agregar Productos</button>
</div>

