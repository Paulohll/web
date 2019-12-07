@extends('template')
@section('style')

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/> -->
@stop
@section('section')


<div class="be-content" >

  <template>

    <panelcuentas :ruta="ruta">
	  
	 
    </panelcuentas>
  </template>

</div>

@stop

@section('script')

	<!-- <script src="{{ asset('public/lib/morrisjs/morris.min.js') }}" type="text/javascript"></script> -->
	<!-- <script src="{{ asset('public/js/app-charts-morris.js') }}" type="text/javascript"></script> -->
	<!-- <script src="{{ asset('public/lib/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.html5.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.flash.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.print.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.colVis.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.bootstrap.js') }}" type="text/javascript"></script>  -->
  <script src="{{ asset('public/js/app.js') }}" type="text/javascript"></script> 
@stop
