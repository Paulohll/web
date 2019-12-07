<div class="panel panel-contrast">
      <div class="panel-heading panel-heading-contrast">
            <strong class='p_nombre_producto'>{{$data_npr}}</strong>
            <span class="panel-subtitle p_unidad_medida">{{$data_upr}}</span>
            @if(count($precioregular) > 0)
            <span class="panel-subtitle">{{$precioregular[0]->precio}}</span>  
            @else
              @if(count($precioestandar) > 0)
               <span class="panel-subtitle">{{$precioestandar[0]->precio}}</span>  
              @else
               <span>No se encontró precio.</span>
              @endif
                
            @endif
                                      
            <span class="mdi mdi-close-circle mdi-close-precio"></span>
            <span class="mdi mdi-check-circle mdi-check-precio"
              data_ipr='{{$data_ipr}}'
              data_ppr='{{$data_ppr}}'
              data_npr='{{$data_npr}}'
              data_upr='{{$data_upr}}'
            ></span>
      </div>

      <div class="panel-heading panel-heading-contrast">
      @if(count($reglas) > 0)
                  <table class="table table-striped table-hover">
                                
                                <tbody>
                                @foreach($reglas as $item)
                                  
                                  <tr class="hover"> 
                                    <td>
                                      <!-- <div class="be-checkbox be-checkbox-sm">
                                        <input id="check4" type="checkbox">
                                        <label for="check4"></label>
                                      </div> -->
                                    </td><td class="cell-detail"><span>{{$item->regla->nombre}}</span><span class="cell-detail-description"> {{$item->regla->descuento}}</span></td>
                                     <td class="cell-detail"><span class="cell-detail-description"><strong> {{$item->regla->tipopago->NOM_CATEGORIA}}</strong></span><span class="cell-detail-description">Min&nbsp;<strong> {{$item->regla->cantidadminima}}</strong></span><span class="cell-detail-description">Max&nbsp;<strong> {{$item->regla->cantidadmaxima}}</strong></span></td>
                                      
                                  </tr>

                                  @endforeach

                                </tbody>
                  </table>
     @else
        <span>No se encontraron descuentos.</span>
     @endif
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

<script type="text/javascript">
    $('.importe').inputmask({ 'alias': 'numeric', 
    'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
    'digitsOptional': false, 
    'prefix': '', 
    'placeholder': '0'});
</script> 