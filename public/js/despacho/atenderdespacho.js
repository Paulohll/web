
$(document).ready(function(){
	var carpeta = $("#carpeta").val();


    $(".despacho").on('click','#modificarfechadeentrega', function() {

        event.preventDefault();
        var _token                  = $('#token').val();
        var data_producto_despacho        = dataproductoatender_fecha_entrega();
        var fechadeentrega          = $('#fechadeentrega').val(); 
        var ordendespacho_id        = $('#ordendespacho_id').val();

        if(fechadeentrega == ''){
            alerterrorajax("Seleccione una fecha de entrega");
            return false;
        }

        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-pedido-atender-modificar-fecha-de-entrega",
            data    :   {
                            _token                      : _token,
                            data_producto_despacho      : data_producto_despacho,
                            fechadeentrega              : fechadeentrega,
                            ordendespacho_id            : ordendespacho_id
                        },
            success: function (data) {
                alertajax("Modificación exitosa");
                $('.lista_orden_atender').html(data);
                $('#modal-entrega').niftyModal('hide');

            },
            error: function (data) {
                error500(data);
            }
        });
    });



    $(".despacho").on('click','.cambiarfechaentrega', function() {

        event.preventDefault();
        data_producto_despacho        = dataproductoatender_fecha_entrega();
        if(data_producto_despacho.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}
        $('#modal-entrega').niftyModal();

    });

    $(".despacho").on('click','#agregarproductosatender', function() {

        event.preventDefault();

        var _token                  = $('#token').val();
        var tabestado               = $('#tabestado').val();

        if(tabestado == 'prod'){

            $('input[type=search]').val('').change();
            $("#despacholopatender").DataTable().search("").draw();
            var data_producto           = dataenviarproducto();

            var ordendespacho_id        = $('#ordendespacho_id').val();

            if(data_producto.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}
            $('#modal-detalledocumento-atender').niftyModal('hide');
            $('.modal-detalledocumento-atender-container').html('');

            $.ajax({

                type    :   "POST",
                url     :   carpeta+"/ajax-modal-agregar-producto-pedido-atender",
                data    :   {
                                _token                  : _token,
                                data_producto           : data_producto,
                                ordendespacho_id        : ordendespacho_id,
                            },    
                success: function (data) {
                    cerrarcargando();
                    alertajax("Producto agregado exitosa");
                    $('.lista_orden_atender').html(data);
                },
                error: function (data) {
                    error500(data);
                }
            });


        }
    });

    $(".despacho").on('keypress','.updatepriceatender', function(e) {

        event.preventDefault();
        var _token                          = $('#token').val();
        var catidad_atender                 = $(this).val();
        var detalle_orden_despacho_id       = $(this).parents('.fila_pedido').attr('data_detalle_orden_despacho');


        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){

            $.ajax({
                
                type    :   "POST",
                url     :   carpeta+"/ajax-modificar-cantidad-atender-producto-id",
                data    :   {
                                _token                      : _token,
                                catidad_atender             : catidad_atender,
                                detalle_orden_despacho_id   : detalle_orden_despacho_id
                            },
                success: function (data) {

                    alertajax("Modificación exitosa");
                    $('.lista_orden_atender').html(data);

                },
                error: function (data) {
                    error500(data);
                }
            });

        }
    });



    $(".despacho").on('click','.agregarproductoatender', function() {

        var _token                      = $('#token').val();
        var ordendespacho_id            = $('#ordendespacho_id').val();

        abrircargando();
        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-modal-lista-orden-atender-producto",
            data    :   {
                            _token              : _token,
                            ordendespacho_id    : ordendespacho_id,
                        },
            success: function (data) {
                cerrarcargando();
                $('.modal-detalledocumento-atender-container').html(data);
                $('#modal-detalledocumento-atender').niftyModal();
            },
            error: function (data) {
                error500(data);
            }
        });

    });



});


function dataproductoatender_fecha_entrega(){
    var data = [];
    $(".table-pedidos-despachos tbody tr").each(function(){

        //debugger;
        data_detalle_orden_despacho     = $(this).attr('data_detalle_orden_despacho');
        check                           = $(this).find('.input_asignar_lp');

        if($(check).is(':checked')){
            data.push({
                data_detalle_orden_despacho     : data_detalle_orden_despacho
            });
        }               

    });
    return data;
}


function dataenviarproducto(){
    var data = [];
    $(".lista_tabla_prod tbody tr").each(function(){
        check                = $(this).find('.input_asignar_prod');
        producto_id          = $(this).attr('data_producto_id');
        cantidad_atender     = $(this).find('.precio_modal').val();

        debugger;

        if($(check).is(':checked')){
            data.push({
                producto_id         : producto_id,
                cantidad_atender    : cantidad_atender
            });
        }               

    });
    return data;
}








