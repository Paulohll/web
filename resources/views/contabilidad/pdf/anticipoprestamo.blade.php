<!DOCTYPE html>
<html lang="es">

<head>
  <title>{{$titulo}}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('public/css/pdf_contabilidad.css') }} "/>

</head>
<body>



  @foreach($listaliquidacioncompra as $index => $item) 
    @include('contabilidad.pdf.section.anticipoprestamo', ['item' => $item])
  @endforeach

</body>
</html>