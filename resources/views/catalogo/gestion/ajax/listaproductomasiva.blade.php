
<table id="tablereglamasivo" class="table table-striped table-hover table-fw-widget listatabla">
  <thead>

    <tr> 
      <th class= 'tabladp'>CLIENTE</th>
      <th class= 'tabladp'>RESPONSABLE</th>
      <th class= 'tabladp'>CANAL</th>
      <th class= 'tabladp'>SUB CANAL</th>
      <th class= 'tabladp'>PRODUCTO</th>
      <th class= 'columna-primary-titulo'>Precio Regular</th>
      <th class='columna-warning'>MODIFICAR</th>

      <th>
        <div class="text-center be-checkbox be-checkbox-sm has-primary">
          <input  type="checkbox"
                  class="todo_asignar input_asignar"
                  id="todo_asignar"
          >
          <label  for="todo_asignar"
                  data-atr = "todas_asignar"
                  class = "checkbox_asignar"                    
                  name="todo_asignar"
            ></label>
        </div>
      </th>

    </tr>
  </thead>
  <tbody>
    @foreach($listadeproductos as $indexp => $itemproducto)
      @foreach($listacliente as $index => $item)
          <tr class='fila_regla'
              data_producto='{{$itemproducto->COD_PRODUCTO}}'
              data_cliente='{{$item->id}}'
              data_empresa='{{$item->COD_EMPR}}'
              data_contrato='{{$item->COD_CONTRATO}}'>


              <td class="cell-detail">
                <span>{{$item->NOM_EMPR}}</span>
                <span class="cell-detail-description-producto">{{$funcion->funciones->data_empresa($item->COD_EMPR)->NOM_EMPR}}</span>
                <span class="cell-detail-description-contrato">{{$item->COD_CONTRATO}}</span>
              </td>

              <td>{{$item->TXT_CATEGORIA_JEFE_VENTA}}</td>
              <td>{{$item->TXT_CATEGORIA_CANAL_VENTA}}</td>
              <td>{{$item->TXT_CATEGORIA_SUB_CANAL}}</td>
              <td>{{$itemproducto->NOM_PRODUCTO}}</td>
              <td class='columna-precio columna-primary'>
                  <i class="mdi mdi-check-circle"></i>
                  {{number_format($funcion->funciones->precio_producto_contrato_empresa($itemproducto->id,$item->COD_CONTRATO,$item->COD_EMPR), 2, '.', ',')}}
              </td>

              <td class='columna-warning'>
                <input type="text"
                       id="precio" 
                       name="precio"
                       class="form-control input-sm dinero updateprice"
                       >
              </td>

              <td>

                  <div class="text-center be-checkbox be-checkbox-sm has-primary">
                    <input  type="checkbox"
                      class="{{$indexp}}{{$index}} input_asignar"
                      id="{{$indexp}}{{$index}}" >

                    <label  for="{{$indexp}}{{$index}}"
                          data-atr = "ver"
                          class = "checkbox checkbox_asignar"                    
                          name="{{$indexp}}{{$index}}"
                    ></label>
                  </div>

              </td>
              
          </tr>
      @endforeach       
    @endforeach
  </tbody>
</table>


<script type="text/javascript">
  $(document).ready(function(){
     App.dataTables();

    $('.dinero').inputmask({ 'alias': 'numeric', 
    'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 
    'digitsOptional': false, 
    'prefix': '', 
    'placeholder': '0'});

  });
</script> 