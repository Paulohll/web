
<table id="despacholop" class="table table table-hover table-fw-widget dt-responsive nowrap" style='width: 100%;'>
  <thead>
    <tr> 
      <th>PRODUCTO</th>
    </tr>
  </thead>
  <tbody>
    @foreach($listaproductos as $item)
      <tr data_producto_id ="{{$item->COD_PRODUCTO}}">

        <td class="cell-detail">
          <span>{{$item->NOM_PRODUCTO}}</span>
          <span class="cell-detail-description-producto">{{$item->NOM_UNIDAD_MEDIDA}}</span>
        </td>

      </tr>                    
    @endforeach
  </tbody>
</table>

@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
       App.dataTables();
    });
  </script> 
@endif