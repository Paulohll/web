
$(document).ready(function(){

	var carpeta = $("#carpeta").val();

    $(".despacho").on('click','.seltab', function() {
        var seltab   = $(this).attr('data_tab');
        $('#tabestado').val(seltab);
    });


    $(".despacho").on('click','.eliminar-producto-despacho', function() {

        event.preventDefault();
        var _token                  = $('#token').val();
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var grupo                   = $('#grupo').val();
        var correlativo             = $('#correlativo').val();
        var fila                    = $(this).parents('.fila_pedido').attr('data_correlativo');
        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-pedido-eliminar-fila",
            data    :   {
                            _token                      : _token,
                            array_detalle_producto      : array_detalle_producto,
                            grupo                       : grupo,
                            correlativo                 : correlativo,
                            fila                        : fila,
                        },
            success: function (data) {
                $('.lista_pedidos_despacho').html(data);
            },
            error: function (data) {
                error500(data);
            }
        });
    });




    $(".despacho").on('click','.crearmobil', function() {

        event.preventDefault();
        var _token                  = $('#token').val();
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var grupo                   = $('#grupo').val();
        var correlativo             = $('#correlativo').val();

        data_producto_pedido        = dataproductopedidos();
        if(data_producto_pedido.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}

        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-pedido-crear-movil",
            data    :   {
                            _token                      : _token,
                            data_producto_pedido        : data_producto_pedido,
                            array_detalle_producto      : array_detalle_producto,
                            grupo                       : grupo,
                            correlativo                 : correlativo,
                        },
            success: function (data) {
                $('.lista_pedidos_despacho').html(data);
            },
            error: function (data) {
                error500(data);
            }
        });

    });




    $(".despacho").on('click','#buscarordenpedidodespacho', function() {

        var _token              = $('#token').val();
        var cuenta_id           = $('#cuenta_id').select2().val();
        /****** VALIDACIONES ********/
        if(cuenta_id.length<=0){
            alerterrorajax("Seleccione un cliente");
            return false;
        }

        abrircargando();
        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-modal-lista-orden-cen-producto",
            data    :   {
                            _token          : _token,
                            cuenta_id       : cuenta_id
                        },
            success: function (data) {
                cerrarcargando();
                $('.modal-detalledocumento-container').html(data);
                $('#modal-detalledocumento').niftyModal();
            },
            error: function (data) {
                error500(data);
            }
        });

    });



    $(".despacho").on('click','.despacholocen', function() {

 
        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-modal-generar-nota-credito",
            data    :   {
                            _token                  : _token,
                            cuenta_id               : cuenta_id,
                            datasproductos          : datasproductos,
                            data_cod_orden_venta    : data_cod_orden_venta,
                            serie                   : serie,
                            motivo_id               : motivo_id,
                            informacionadicional    : informacionadicional,
                            idopcion                : idopcion,
                        },    
            success: function (data) {
                cerrarcargando();
                $('.modal-nota_credito_generada').html(data);
                $('#nota_credito_generada').niftyModal();

            },
            error: function (data) {
                error500(data);
            }
        });

    });


    $(".despacho").on('click','#agregarproductos', function() {

        event.preventDefault();

        var _token                  = $('#token').val();
        var grupo                   = $('#grupo').val();
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var correlativo             = $('#correlativo').val();
        var tabestado               = $('#tabestado').val();

        if(tabestado == 'prod'){

            $('input[type=search]').val('').change();
            $("#despacholop").DataTable().search("").draw();
            data_producto = dataenviarproducto();
            if(data_producto.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}
            $('#modal-detalledocumento').niftyModal('hide');


        }else{

            $('input[type=search]').val('').change();
            $("#despacholocen").DataTable().search("").draw();
            data_orden_cen = dataenviar();
            if(data_orden_cen.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}
            $('#modal-detalledocumento').niftyModal('hide');


            $.ajax({

                type    :   "POST",
                url     :   carpeta+"/ajax-modal-agregar-orden-cen-pedido",
                data    :   {
                                _token                  : _token,
                                data_orden_cen          : data_orden_cen,
                                grupo                   : grupo,
                                correlativo             : correlativo,
                                array_detalle_producto  : array_detalle_producto,
                            },    
                success: function (data) {
                    cerrarcargando();
                    $('.lista_pedidos_despacho').html(data);
                },
                error: function (data) {
                    error500(data);
                }
            });


        }









    });
});




function dataenviarproducto(){
    var data = [];
    $(".lista_tabla_prod tbody tr").each(function(){
        check                = $(this).find('.input_asignar_prod');
        producto_id          = $(this).attr('data_producto_id');

        if($(check).is(':checked')){
            data.push({
                producto_id     : producto_id
            });
        }               

    });
    return data;
}


function dataenviar(){
    var data = [];
    $(".lista_tabla_oc tbody tr").each(function(){

        check           = $(this).find('.input_asignar_oc');
        ordencen_id     = $(this).attr('data_orden_id');

        if($(check).is(':checked')){
            data.push({
                ordencen_id     : ordencen_id
            });
        }               

    });
    return data;
}


function dataproductopedidos(){
    var data = [];
    $(".table-pedidos-despachos tbody tr").each(function(){

        //debugger;
        correlativo     = $(this).attr('data_correlativo');
        check           = $(this).find('.input_asignar_lp');

        if($(check).is(':checked')){
            data.push({
                correlativo     : correlativo
            });
        }               

    });
    return data;
}







