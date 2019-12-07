

<table id="thorario" class="table table-striped table-striped dt-responsive nowrap listatabla" style='width: 100%;'>

  <thead>
    <tr>
      <th>Local</th>      
      <th>Area</th>      
      <th>Trabajador</th>
      <th class='text-center'>Lunes</th>
      <th class='text-center'>Martes</th>
      <th class='text-center'>Miercoles</th>
      <th class='text-center'>Jueves</th>
      <th class='text-center'>Viernes</th>
      <th class='text-center'>Sabado</th>
      <th class='text-center'>Domingo</th>     
    </tr>
  </thead>
  <tbody>

    @foreach($listahorario as $item)
      <tr>
        <td class='cell-detail'></td>
        <td class="cell-detail"></td>
        <td class="cell-detail"></td>
        <td class="cell-detail"></td>
      </tr>                    
    @endforeach

  </tbody>
</table>

<script type="text/javascript">
  $(document).ready(function(){
     App.dataTables();
  });
</script> 