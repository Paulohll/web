<table id="despacholocen" class="table table table-hover table-fw-widget dt-responsive nowrap" style='width: 100%;'>
  <thead>
    <tr> 
      <th>Cod. Orden</th>
      <th>Orden Cen</th>
      <th>Fecha Orden</th>
      <th>Cliente</th>
    </tr>
  </thead>
  <tbody>
    @while ($row = $listaordencen->fetch())
      <tr>
        <td
        class='filaoc'
        data_orden_id="{{$row['COD_ORDEN']}}"
        >
          {{$row['COD_ORDEN']}}
        </td>
        <td>{{$row['NRO_ORDEN_CEN']}}</td>
        <td>{{date_format(date_create($row['FEC_ORDEN']), 'd-m-Y')}}</td>
        <td>{{$row['TXT_EMPR_CLIENTE']}}</td>
      </tr>                    
      @endwhile
  </tbody>
</table>


