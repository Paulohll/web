<table class="table table-striped table-borderless">
  <thead>

    <tr>
      <th class='center columna_1'>DATOS FILTRO</th>
      <th colspan="2" class='center columna_1'>PRECIO REGULAR</th>       
      <th colspan="4" class='center columna_1'>REGLAS</th>                    
    </tr> 

    <tr>
      <th class='columna_1'>Nombre</th>
      <th class='columna_1'>Precio</th>
      <th class='columna_1'>Departamento</th>
      <th class='columna_2'>Promoción o Descuento</th>
      <th class='columna_2 warning'>Nota Credito</th>
      <th class='columna_2 danger'>Cupón</th>
      <th class='columna_2 success'>Negociación</th>   
    </tr>
  </thead>
  <tbody class="no-border-x">
    @foreach($listadeproductos as $itemproducto)
      @foreach($listacliente as $index => $item)
      <tr   class='fila_regla'
            data_producto='{{$itemproducto->COD_PRODUCTO}}'
            data_cliente='{{$item->id}}'
            data_contrato='{{$item->COD_CONTRATO}}'
      >


        <td class="cell-detail">
          <span>{{$item->NOM_EMPR}}</span>
          <span class="cell-detail-description-producto">{{$itemproducto->NOM_PRODUCTO}}</span>
          <span class="cell-detail-description-contrato">{{$item->CONTRATO}}</span>
        </td>


        <td class="cell-detail">


            @if($funcion->funciones->favorito_precio_producto_contrato($itemproducto->id,$item->COD_CONTRATO))
              <span class="cambiar-contrato mdi mdi-file-text file-text-{{$funcion->funciones->tiene_contrato_activo($itemproducto->id,$item->COD_CONTRATO)}}" 
                    data_sw = "{{$funcion->funciones->tiene_contrato_activo($itemproducto->id,$item->COD_CONTRATO)}}"
              ></span>
            @endif

            <input type="text"  
                   id="precio" 
                   name="precio"
                   value='{{$funcion->funciones->precio_producto_contrato($itemproducto->id,$item->COD_CONTRATO)}}'
                   class="form-control input-sm dinero updateprice"
                   >

        </td>

        <td class="relative">

            <div class='etprd{{$itemproducto->COD_PRODUCTO}}{{$item->id}}'
                 data_fila='prd'>

                @include('regla.listado.ajax.listaprecioregular',
                         [
                          'producto_id'                     => $itemproducto->COD_PRODUCTO,
                          'cliente_id'                      => $item->id,
                          'contrato_id'                     => $item->COD_CONTRATO,
                          'listareglaproductoclientes'      => $listareglaproductoclientes,
                          'tipo'                            => 'PRD',
                          'color'                           => 'success'
                         ])

            </div>


            <span class="badge badge-success precio-regular-edit"
                  data_nombre='PRECIO REGULAR'
                  data_nombreselect='precio regular'
                  data_tipo='PRD'
                  data_prefijo='prd'
                  data_color='success'
                  data_color_modal='colored-header-success'>
              <span 
                    class="md-trigger icon mdi mdi-edit popover-precio-pr-x" 
              ></span>
            </span>


        </td>


        <td class="relative">

            <div class='etpov{{$itemproducto->COD_PRODUCTO}}{{$item->id}}'
                 data_fila='pov'>

                @include('regla.listado.ajax.etiquetas',
                         [
                          'producto_id'                     => $itemproducto->COD_PRODUCTO,
                          'cliente_id'                      => $item->id,
                          'contrato_id'                     => $item->COD_CONTRATO,
                          'listareglaproductoclientes'      => $listareglaproductoclientes,
                          'tipo'                            => 'POV',
                          'color'                           => 'primary'
                         ])

            </div>

            <span class="badge badge-primary popover-edit"
                  data_nombre='PRECIO'
                  data_nombreselect='precio'
                  data_tipo='POV'
                  data_prefijo='pov'
                  data_color='primary'
                  data_color_modal='colored-header-primary'>
              <span 
                    class="md-trigger icon mdi mdi-edit popover-precio-ov-x" 
              ></span>
            </span>


        </td>
        <td class="relative">
          
            <div class='etpnc{{$itemproducto->COD_PRODUCTO}}{{$item->id}}'
                 data_fila='pnc'>

                @include('regla.listado.ajax.etiquetas',
                         [
                          'producto_id'                     => $itemproducto->COD_PRODUCTO,
                          'cliente_id'                      => $item->id,
                          'contrato_id'                     => $item->COD_CONTRATO,
                          'listareglaproductoclientes'      => $listareglaproductoclientes,
                          'tipo'                            => 'PNC',
                          'color'                           => 'warning'
                         ])

            </div>

            <span class="badge badge-warning popover-edit"
                  data_nombre='NOTA CREDITO'
                  data_nombreselect='nota credito'
                  data_tipo='PNC'
                  data_prefijo='pnc'
                  data_color='warning'
                  data_color_modal='colored-header-warning'>
              <span 
                    class="md-trigger icon mdi mdi-edit popover-precio-nc-x" 
              ></span>
            </span>


        </td>

        
        <td class="relative">

            <div class='etcup{{$itemproducto->COD_PRODUCTO}}{{$item->id}}'
                 data_fila='cup'>

                @include('regla.listado.ajax.etiquetas',
                         [
                          'producto_id'                     => $itemproducto->COD_PRODUCTO,
                          'cliente_id'                      => $item->id,
                          'contrato_id'                     => $item->COD_CONTRATO,
                          'listareglaproductoclientes'      => $listareglaproductoclientes,
                          'tipo'                            => 'CUP',
                          'color'                           => 'danger'
                         ])

            </div>

            <span class="badge badge-danger popover-edit"
                  data_nombre='CUPON'
                  data_nombreselect='cupon'
                  data_tipo='CUP'
                  data_prefijo='cup'
                  data_color='danger'
                  data_color_modal='colored-header-danger'>
              <span 
                    class="md-trigger icon mdi mdi-edit popover-cupon-x" 
              ></span>
            </span>

        </td>

        
        <td class="relative">

            <div  class='etneg{{$itemproducto->COD_PRODUCTO}}{{$item->id}}'
                  data_fila='neg'>


                @include('regla.listado.ajax.etiquetas',
                         [
                          'producto_id'                     => $itemproducto->COD_PRODUCTO,
                          'cliente_id'                      => $item->id,
                          'contrato_id'                     => $item->COD_CONTRATO,
                          'listareglaproductoclientes'      => $listareglaproductoclientes,
                          'tipo'                            => 'NEG',
                          'color'                           => 'success'
                         ])

            </div>

            <span class="badge badge-success popover-edit"
                  data_nombre='NEGOCIACION'
                  data_nombreselect='negociacion'
                  data_tipo='NEG'
                  data_prefijo='neg'
                  data_color='success'
                  data_color_modal='colored-header-success'>
              <span 
                    class="md-trigger icon mdi mdi-edit  popover-negociacion-x" 
              ></span>
            </span>

        </td>
        
        
      </tr>
      @endforeach
    @endforeach
                          
  </tbody>
</table> 


<div class="col-sm-7">
{!! $listacliente->appends(Request::only(['cliente_select','producto_select']))->render() !!}
</div>