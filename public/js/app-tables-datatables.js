var App = (function () {
  'use strict';
        //console.log("entro");
  App.dataTables = function( ){

    //We use this to apply style to certain elements
    $.extend( true, $.fn.dataTable.defaults, {
      dom:
        "<'row be-datatable-header'<'col-sm-6'l><'col-sm-6'f>>" +
        "<'row be-datatable-body'<'col-sm-12'tr>>" +
        "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
    } );




    var collapsedGroups = {};
    var groupColumn = 2;
    var table = $('#table_group').DataTable({
            "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
            "bPaginate": false,

            "oLanguage": {
                "sSearch": ""
            },
            responsive: true,
            "columnDefs": [
                { "visible": false, "targets": groupColumn }
            ],
            "order": [[ groupColumn, 'desc' ]],
            "displayLength": 25,

            "drawCallback": function ( settings ) {

                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;
     
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {

                    if ( last !== group ) {
                        $(rows).eq(i).before(
                            '<tr class="group  group-start '+i+'" data-name="'+group+'"><td colspan="8">'+group+'</td></tr>'
                        );
                        last = group;
                    }else{
                        var collapsed = !!collapsedGroups[group];
                        api.rows( {page:'current'} ).nodes().each(function (r) {
                            $(this).css("display","table-row");
                            $(this).attr('data-valor', group);
                        });

                    }
                } );

            },


    } );


   $('#table_group tbody').on('click', 'tr.group-start', function () {


        var name    =   $(this).data('name');
        var sw      =   0;



        if ($(this).hasClass('collapsed')){

            //abrir group
            $('#table_group tbody tr').each(function (){

                var data_name = $(this).attr('data-name');

                if(data_name == name || sw == 1){
                    sw = 1;
                    $(this).css('display','table-row');
                }
                if(typeof(data_name)  != "undefined"){
                    if(sw == 1 &&  data_name != name){
                        sw = 0;
                    }
                }
            });

            $(this).removeClass( "collapsed" )

        }else{

            //abrir group
            $('#table_group tbody tr').each(function (){

                var data_name = $(this).attr('data-name');

                if(data_name == name || sw == 1){
                    sw = 1;
                    if ($(this).hasClass('odd') || $(this).hasClass('even')){
                        $(this).css('display','none');
                    }
                }
                if(typeof(data_name)  != "undefined"){
                    if(sw == 1 &&  data_name != name){
                        sw = 0;
                    }
                }
            });

            $(this).addClass("collapsed");

        }
    });


    $("#despacholop").dataTable({
        "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
        "bPaginate": false
    });

    $("#despacholocen").dataTable({
        "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
        order : [[ 2, "desc" ]],
        "bPaginate": false
    });


    $("#tableperfiles").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 2, "desc" ]],
        "bPaginate": false
    });

    $("#table1").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 2, "desc" ]],
    });

    $("#tablepedido").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 0, "desc" ]],
        "bPaginate": false,
        "bInfo": false,
        "oLanguage": {
            "sSearch": ""
        },
        responsive: true,
    });


    $("#tablatomapedido").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 1, "desc" ]],
        responsive: true,
        columnDefs: [ 
            { orderable: false, targets: -8 }
        ]
    });

    $("#tablereglamasivo").dataTable({
        "lengthMenu": [[1500, 2000, -1], [1500, 2000, "All"]],
        order : [[ 0, "asc" ]],
        columnDefs: [ 
            {
                className: 'control',
                orderable: false,
                targets:   -1
            },
            { orderable: false, targets: -1 },
            { orderable: false, targets: -2 }
        ],
    });

    $("#tablenotacredito").dataTable({
        "lengthMenu": [[3000, 4000, -1], [3000, 4000, "All"]],
        order : [[ 2, "asc" ]],
        "bLengthChange" : false,
        columnDefs: [ 
            { orderable: false, targets: -4 },
            { orderable: false, targets: -2 },
            { orderable: false, targets: -1 }
        ]
    });

    $("#tableordenventa").dataTable({
        "lengthMenu": [[3000, 4000, -1], [3000, 4000, "All"]],
        order : [[ 1, "desc" ]],
        "bLengthChange" : false,
        columnDefs: [ 
            { orderable: false, targets: -4 },
            { orderable: false, targets: -2 },
            { orderable: false, targets: -1 }
        ]
    });




    $("#tablenotacreditoeliminar").dataTable({
        "lengthMenu": [[3000, 4000, -1], [3000, 4000, "All"]],
        order : [[ 2, "asc" ]],
        "bLengthChange" : false,
        columnDefs: [ 
            { orderable: false, targets: -4 },
            { orderable: false, targets: -1 }
        ]
    });

    $("#tablenotacreditover").dataTable({
        "lengthMenu": [[3000, 4000, -1], [3000, 4000, "All"]],
        order : [[ 2, "asc" ]],
        "bLengthChange" : false,
        columnDefs: [ 
            { orderable: false, targets: -3 },
            { orderable: false, targets: -1 }
        ]
    });



    $("#tablenegociaciones").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 5, "asc" ]],
    });
    $("#tablecupones").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 6, "asc" ]],
    });
    $("#tableprecios").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        order : [[ 1, "desc" ]],
    });


    $("#tableclientes").dataTable({
        "lengthMenu": [[1000, 2000, -1], [1000, 2000, "All"]],
    });


    $("#tableclientetp").dataTable({
        "lengthMenu": [[5000, 10000, -1], [5000, 10000, "All"]],
        "bPaginate": false,
        "bInfo": false,
        "oLanguage": {
            "sSearch": ""
        }
    });


    $("#tableproductotp").dataTable({
        "lengthMenu": [[5000, 10000, -1], [5000, 10000, "All"]],
        "bPaginate": false,
        "bInfo": false,
        "oLanguage": {
            "sSearch": ""
        }
    });



    $("#tablereporte").dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        "ordering": false,
        "bLengthChange" : false,
        "bPaginate": false  
    });

    //Remove search & paging dropdown
    $("#table2").dataTable({
      pageLength: 6,
      dom:  "<'row be-datatable-body'<'col-sm-12'tr>>" +
            "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
    });

    //Enable toolbar button functions
    $("#table3").dataTable({
      buttons: [
        'copy', 'excel', 'pdf', 'print'
      ],
      "lengthMenu": [[6, 10, 25, 50, -1], [6, 10, 25, 50, "All"]],
      dom:  "<'row be-datatable-header'<'col-sm-6'l><'col-sm-6 text-right'B>>" +
            "<'row be-datatable-body'<'col-sm-12'tr>>" +
            "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
    });


    $('.listajax #tfactura').dataTable({
        order : [[ 1, "desc" ]],
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columnDefs: [ 
            {
                className: 'control',
                orderable: false,
                targets:   -1
            },
            { orderable: false, targets: -11 }
        ],
    } );


    var oTable=$('.listajax #thorario').dataTable({
        "lengthMenu": [[500, 1000, -1], [500, 1000, "All"]],
        "columnDefs": [
            { "targets": [ 0 ], "visible": false },
            { "targets": [ 1 ], "visible": false },            
            //{ "orderable": false, "targets": 1 },
         { 
          orderData: [[0, 'asc'], [1, 'desc']]//sort by age then by salary 
         },
            { "orderable": false, "targets": 2 },
            { "orderable": false, "targets": 3 },
            { "orderable": false, "targets": 4 },
            { "orderable": false, "targets": 5 },
            { "orderable": false, "targets": 6 },
            { "orderable": false, "targets": 7 }, 
            { "orderable": false, "targets": 8 },
            { "orderable": false, "targets": 9 }                                         
        ]


    } );

    oTable.fnSort([[0,"asc"], [1,"asc"]]); 



    $('.listajax #rfactura').dataTable({
        order : [[ 3, "desc" ]],
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columnDefs: [ 
            {
                className: 'control',
                orderable: false,
                targets:   -1
            },
            { orderable: false, targets: -11 }
        ],


    } );





  };

  return App;
})(App || {});
