<div class='listaproductos'>
  <table id="tableproductotp" class="table table-striped table-hover table-fw-widget">
    <thead>
      <tr>
        <th>PRODUCTOS</th>
      </tr>
    </thead>
    <tbody>
      @foreach($listaproductos as $item)
        <tr class='filaproducto'
            data_ipr='{{Hashids::encode(substr($item->COD_PRODUCTO, -13))}}'
            data_ppr='{{substr($item->COD_PRODUCTO, 0, 3)}}'
            data_npr='{{$item->NOM_PRODUCTO}}'
            data_upr='{{$item->NOM_UNIDAD_MEDIDA}}'>
          <td class="cell-detail">
            <span>{{$item->NOM_PRODUCTO}}</span>
            <span class="cell-detail-description-producto">{{$item->NOM_UNIDAD_MEDIDA}}</span>
          </td>
        </tr>                    
      @endforeach

    </tbody>
  </table>
</div>

<div class="row precioproducto">
  <div class="col-sm-12 col-mobil-top ajaxreglaproducto">
    <div class="panel panel-contrast">
      <div class="panel-heading panel-heading-contrast">
            <strong class='p_nombre_producto'>Nombre producto</strong>
            <span class="panel-subtitle p_unidad_medida">unidad medida</span>                          
            <span class="mdi mdi-close-circle mdi-close-precio"></span>
            <span class="mdi mdi-check-circle mdi-check-precio"
              data_ipr=''
              data_ppr=''
              data_npr=''
              data_upr=''
            ></span>
      </div>
    </div>
    <div class="panel-body">
    
      <div class="col-sm-12">
        <div class="form-group">
          <label class="col-sm-12 control-label">
            Cantidad
          </label>
          <div class="col-sm-12">
            <div class="input-group_mobil">
              <input  type="text"
                      id="cantidad" name='cantidad' 
                      value="" 
                      placeholder="Cantidad"
                      required = "" class="form-control input-sm importe" data-parsley-type="number"
                      autocomplete="off" data-aw="1"/>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group">
          <label class="col-sm-12 control-label">
            Precio
          </label>
          <div class="col-sm-12">
            <div class="input-group_mobil">
              <input  type="text"
                      id="precio" name='precio' 
                      value="" 
                      placeholder="Precio"
                      required = "" class="form-control input-sm importe" data-parsley-type="number"
                      autocomplete="off" data-aw="2"/>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div>
</div>

<!--
<div class='row-menu'>
  <div class='row'>
    <div class="col-sm-12 col-mobil">
      <div class="col-fr-2 col-atras">
        <span class="mdi mdi-arrow-left"></span>
      </div> 
      <div class="col-fr-10 col-total">
        <strong>Seleccione un producto</strong>
      </div> 
    </div>
  </div>
</div>-->