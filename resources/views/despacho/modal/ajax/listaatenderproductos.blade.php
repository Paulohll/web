
<table id="despacholopatender" class="table table table-hover table-fw-widget dt-responsive nowrap lista_tabla_prod" style='width: 100%;'>
  <thead>
    <tr> 
      <th>PRODUCTO</th>
      <th>UNIDAD</th>
      <th>ATENDER</th>
      <th>SEL</th>
    </tr>
  </thead>
  <tbody>
    @foreach($listaproductos as $item)
      <tr 
        class='filaprod'
        data_producto_id ="{{$item->COD_PRODUCTO}}"
        >

        <td>
          {{$item->NOM_PRODUCTO}}
        </td>
        <td>
          {{$item->NOM_UNIDAD_MEDIDA}}
        </td>
        <td>

            <input type="text"
             id="{{$item->COD_PRODUCTO}}can" 
             name="catidad_atender_modal"
             value="0.00"
             class="form-control input-sm dinero precio_modal"
            >

        </td>
        <td>
          <div class="text-center be-checkbox be-checkbox-sm has-primary">
            <input  
              type="checkbox"
              class="{{$item->COD_PRODUCTO}} input_asignar_prod"
              id="{{$item->COD_PRODUCTO}}" >

            <label  for="{{$item->COD_PRODUCTO}}"
                  data-atr = "ver"
                  class = "checkbox checkbox_asignar_prod"                    
                  name="{{$item->COD_PRODUCTO}}"
            ></label>
          </div>
        </td>
      </tr>                    
    @endforeach
  </tbody>
</table>

@if(isset($ajax))
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
@endif