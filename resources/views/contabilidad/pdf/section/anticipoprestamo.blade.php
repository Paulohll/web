<div class="menu">

    <div class="left">

        <img src="{{ asset('public/img/empresa/'.$item->LOGO.'.jpg') }}" alt="logo" width="140" height="70">   
    </div>

    <div class="centro">
        <h1>{{$item->TITULO}}</h1> 
        <h4>{{$item->DIRECCION}}</h4>   
    </div>

    <div class="right">
        <h3>R.U.C. {{$item->RUC}}</h3> 
        <h3>NOTA DE INGRESO</h3>
        <h3>{{$item->SERIE}} - {{$item->CORRELATIVO}}</h3> 
    </div>
</div>
<div class="top">
    <div class="det1">
      <p>
        <strong>CLIENTE :</strong> {{$item->EMPRESA_EMITE}}
      </p>            
      <p>
        <strong>RUC/DNI :</strong>  20600004027
      </p>
      <p>
        <strong>PROVEEDOR DE ARROZ CASCARA :</strong> {{$item->EMPR_DOC_FORMAL}}
      </p>
      <p>
        <strong>FECHA DE INGRESO :</strong> {{date_format(date_create($item->FEC_AUTORIZACION), 'd/m/Y')}}
      </p>
      <p>
        <strong>HORA DE INGRESO :</strong> {{date_format(date_create($item->HORA_AUTORIZACION), 'H:i:s A')}}
      </p>
    </div>
</div>


  <table width="300" height="50" 
      cellspacing="0" cellpadding="0" border="1"  align="center">
    <tr>
      <th class='titulo'>CODIGO</th>
      <th class='titulo'>CANTIDAD</th>
      <th class='titulo'>DESCRIPCION</th>
      <th class='titulo'>UNIDAD</th>
      <th class='titulo'>PESO NETO</th>
    </tr>

    <tr>
      <td class='titulo'>{{$item->COD_PRODUCTO}}</td>
      <td class='titulo'>{{round($item->CANTIDAD_CALCULADO,0)}}</td>
      <td class='titulo'>{{$item->TXT_NOMBRE_PRODUCTO}}</td>
      <td class='titulo'>{{$item->UNIDAD}}</td>
      <td class='titulo'>{{number_format($item->PESONETO, 4, '.', ',')}}</td>
    </tr>

    @for ($i = 0; $i < 13; $i++)
      <tr>
        <td class='titulo'></td>
        <td class='titulo'></td>
        <td class='titulo'></td>
        <td class='titulo'></td>
        <td class='titulo'></td>
      </tr>
    @endfor


  </table>

  <table width="300" 
      cellspacing="0" cellpadding="0" border="1"  align="center">
    <tr>
      <th width="100" class='thpie'>
        <div class='thfooter thf'>RECEPCION:</div>
      </th>
      <th width="100" class='thpie'>
        <div class='thfooter thf'>VERIFICADO:</div>
      </th>
      <th width="100" class='thpie'>
        <div class='thfooter'>OBSERVACIONES:<br>
          Compra realizada en Molino del cliente {{$item->EMPR_DOC_FORMAL}} al sr(a) {{$item->EMPRESA_EMITE}} identificado con DNI {{$item->NRO_RUC_FORMAL}}
        </div>
      </th>
    </tr>
  </table>