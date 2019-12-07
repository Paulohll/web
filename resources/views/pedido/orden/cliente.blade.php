<div class='listaclientes'>
  <table id="tableclientetp" class="table table-striped table-hover table-fw-widget">
  <thead>
    <tr>
      <th>CLIENTES</th>
    </tr>
  </thead>
  <tbody>
    @foreach($listaclientes as $item)
      <tr class='filapedido'
          data_icl='{{Hashids::encode(substr($item->id, -10))}}'
          data_pcl='{{substr($item->id, 0, 6)}}'
          data_icu='{{Hashids::encode(substr($item->COD_CONTRATO, -10))}}'
          data_pcu='{{substr($item->COD_CONTRATO, 0, 6)}}'
          data_ncl='{{$item->NOM_EMPR}}'
          data_dcl='{{$item->NRO_DOCUMENTO}}'
          data_ccl='{{$item->CONTRATO}}'
          >
        <td class="cell-detail">
          <span>{{$item->NOM_EMPR}}</span>
          <span class="cell-detail-description-producto">{{$item->NRO_DOCUMENTO}}</span>
          <span class="cell-detail-description-contrato">{{$item->CONTRATO}}</span>
        </td>
      </tr>                    
    @endforeach

  </tbody>
  </table>
</div>

<div class="row direccioncliente">
  <div class="col-sm-12 col-mobil-top ajaxdirecciones">

      <div class="panel panel-contrast">
        <div class="panel-heading panel-heading-contrast">
              <strong class='c_nombre_cliente'>Nombre cliente</strong>
              <span class="panel-subtitle c_documento-cuenta">documento - cuenta</span>                          
              <span class="mdi mdi-close-circle mdi-close-cliente"></span>
              <span class="mdi mdi-check-circle mdi-check-cliente"
                data_icl=''
                data_pcl=''
              ></span>
        </div>
      </div>
      <div class="panel-body">
          
      </div>

  </div>
</div>