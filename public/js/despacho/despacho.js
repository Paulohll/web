
$(document).ready(function(){

	var carpeta = $("#carpeta").val();


    $(".listadespacho").on('click','#buscarpedidoatender', function() {

        var _token              = $('#token').val();
        var fechainicio         = $('#fechainicio').val();        
        var fechafin            = $('#fechafin').val();
        var opcion_id           = $('#opcion_id').val();
        /****** VALIDACIONES ********/

        if(fechainicio == ''){
            alerterrorajax("Seleccione una fecha de inicio");
            return false;
        }

        if(fechafin == ''){
            alerterrorajax("Seleccione una fecha de fin");
            return false;
        } 

        abrircargando(); 
        $(".listatablapedidosatender").html("");

        $.ajax({
            type    :   "POST",
            url     :   carpeta+"/ajax-lista-atender-pedidos-despacho",
            data    :   {
                            _token          : _token,
                            fechainicio     : fechainicio,
                            fechafin        : fechafin                            
                        },
            success: function (data) {
                cerrarcargando();
                $(".listatablapedidosatender").html(data);                
            },
            error: function (data) {
                cerrarcargando();
                if(data.status = 500){
                    var contenido = $(data.responseText);
                    alerterror505ajax($(contenido).find('.trace-message').html());  
                    console.log($(contenido).find('.trace-message').html());     
                }
            }
        });

    });



    $(".listadespacho").on('click','#buscarpedidodespacho', function() {

        var _token              = $('#token').val();
        var fechainicio         = $('#fechainicio').val();        
        var fechafin            = $('#fechafin').val();

        /****** VALIDACIONES ********/

        if(fechainicio == ''){
            alerterrorajax("Seleccione una fecha de inicio");
            return false;
        }

        if(fechafin == ''){
            alerterrorajax("Seleccione una fecha de fin");
            return false;
        } 

        abrircargando(); 
        $(".listatablapedidosdespachos").html("");

        $.ajax({
            type    :   "POST",
            url     :   carpeta+"/ajax-lista-pedidos-despacho",
            data    :   {
                            _token          : _token,
                            fechainicio     : fechainicio,
                            fechafin        : fechafin                            
                        },
            success: function (data) {
                cerrarcargando();
                $(".listatablapedidosdespachos").html(data);                
            },
            error: function (data) {
                cerrarcargando();
                if(data.status = 500){
                    var contenido = $(data.responseText);
                    alerterror505ajax($(contenido).find('.trace-message').html());  
                    console.log($(contenido).find('.trace-message').html());     
                }
            }
        });

    });


    $(".despacho").on('click','#modificarconfiguracionprouducto', function() {

        event.preventDefault();
        var _token                  = $('#token').val();
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var grupo                   = $('#grupo').val();
        var correlativo             = $('#correlativo').val();
        var cantidad_bolsa_saco     = $('#cantidad_bolsa_saco').val(); 
        var cantidad_saco_palet     = $('#cantidad_saco_palet').val();
        var producto_id             = $('#producto_configuracion_id').val();
        var opcion_id               = $('#opcion').val();

        if(cantidad_bolsa_saco == ''){alerterrorajax("Ingrese cantidad de bolsas que contiene un SACO");return false;}
        if(cantidad_saco_palet == ''){alerterrorajax("Ingrese cantidad de sacos que contiene un PALET");return false;}


        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-modificar-configuracion-del-producto",
            data    :   {
                            _token                      : _token,
                            array_detalle_producto      : array_detalle_producto,
                            grupo                       : grupo,
                            correlativo                 : correlativo,
                            cantidad_bolsa_saco         : cantidad_bolsa_saco,
                            cantidad_saco_palet         : cantidad_saco_palet,
                            producto_id                 : producto_id,
                            opcion_id                   : opcion_id,
                        },
            success: function (data) {
                alertajax("Modificación exitosa");
                $('.lista_pedidos_despacho').html(data);
                $('#modal-cofiguracion-cantidad').niftyModal('hide');
            },
            error: function (data) {
                error500(data);
            }
        });


    });


    $(".despacho").on('click','.configuracion-despacho-cantidad', function() {

        var _token              = $('#token').val();
        var producto_id         = $(this).parents('.fila_pedido').attr('data_producto'); 

        abrircargando();
        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-modal-configuracion-producto-cantidad",
            data    :   {
                            _token          : _token,
                            producto_id     : producto_id
                        },
            success: function (data) {
                cerrarcargando();
                $('.modal-configuracion-container').html(data);
                $('#modal-cofiguracion-cantidad').niftyModal();
            },
            error: function (data) {
                error500(data);
            }
        });
    });


    $(".despacho").on('click','.cambiarfechaentrega', function() {

        event.preventDefault();
        data_producto_pedido        = dataproductopedidos();
        if(data_producto_pedido.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}
        $('#modal-entrega').niftyModal();

    });


    $(".despacho").on('click','#modificarfechadeentrega', function() {

        event.preventDefault();
        var _token                  = $('#token').val();
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var grupo                   = $('#grupo').val();
        var correlativo             = $('#correlativo').val();
        var data_producto_pedido    = dataproductopedidos();
        var fechadeentrega          = $('#fechadeentrega').val(); 
        var opcion_id               = $('#opcion').val();

        if(fechadeentrega == ''){
            alerterrorajax("Seleccione una fecha de entrega");
            return false;
        }

        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-pedido-modificar-fecha-de-entrega",
            data    :   {
                            _token                      : _token,
                            data_producto_pedido        : data_producto_pedido,
                            array_detalle_producto      : array_detalle_producto,
                            grupo                       : grupo,
                            correlativo                 : correlativo,
                            fechadeentrega              : fechadeentrega,
                            opcion_id                   : opcion_id,
                        },
            success: function (data) {
                alertajax("Modificación exitosa");
                $('.lista_pedidos_despacho').html(data);
                $('#modal-entrega').niftyModal('hide');

            },
            error: function (data) {
                error500(data);
            }
        });
    });


    $(".despacho").on('keypress','.updatepriced', function(e) {

        event.preventDefault();
        var _token                  = $('#token').val();
        var cantidad                = $(this).val();
        var fila                    = $(this).parents('.fila_pedido').attr('data_correlativo');
        var producto_id             = $(this).parents('.fila_pedido').attr('data_producto');  
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var correlativo             = $('#correlativo').val();
        var grupo                   = $('#grupo').val();
        var opcion_id               = $('#opcion').val();


        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){


            $.ajax({
                
                type    :   "POST",
                url     :   carpeta+"/ajax-modificar-cantidad-producto-fila",
                data    :   {
                                _token                      : _token,
                                array_detalle_producto      : array_detalle_producto,
                                cantidad                    : cantidad,
                                producto_id                 : producto_id,
                                correlativo                 : correlativo,
                                grupo                       : grupo,
                                opcion_id                   : opcion_id,
                                fila                        : fila
                            },
                success: function (data) {

                    alertajax("Modificación exitosa");
                    $('.lista_pedidos_despacho').html(data);

                },
                error: function (data) {
                    error500(data);
                }
            });

        }
    });

    $(".despacho").on('keypress','.updatepricemuestrad', function(e) {

        event.preventDefault();
        var _token                  = $('#token').val();
        var muestra                 = $(this).val();
        var fila                    = $(this).parents('.fila_pedido').attr('data_correlativo');
        var producto_id             = $(this).parents('.fila_pedido').attr('data_producto');  
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var correlativo             = $('#correlativo').val();
        var grupo                   = $('#grupo').val();
        var opcion_id               = $('#opcion').val();

        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){


            $.ajax({
                
                type    :   "POST",
                url     :   carpeta+"/ajax-modificar-muestra-producto-fila",
                data    :   {
                                _token                      : _token,
                                array_detalle_producto      : array_detalle_producto,
                                muestra                    : muestra,
                                producto_id                 : producto_id,
                                correlativo                 : correlativo,
                                grupo                       : grupo,
                                opcion_id                   : opcion_id,
                                fila                        : fila
                            },
                success: function (data) {

                    alertajax("Modificación exitosa");
                    $('.lista_pedidos_despacho').html(data);

                },
                error: function (data) {
                    error500(data);
                }
            });

        }
    });







    $(".despacho").on('click','.seltab', function() {
        var seltab   = $(this).attr('data_tab');
        $('#tabestado').val(seltab);
    });


    $(".despacho").on('click','.input_asignar_gop', function() {

        var data_check_oc     = $(this).attr('data_check_oc');
        if($(this).is(':checked')){
            check = true;
        }else{
            check = false;
        }  
        $(".table-pedidos-despachos tbody tr").each(function(){

            data_check_sel  = $(this).find('.input_asignar_lp').attr('data_check_sel');
            if(data_check_oc == data_check_sel){
                $(this).find('.input_asignar_lp').prop('checked', check);
            }               
        });

    });


    $(".despacho").on('click','.eliminar-producto-despacho', function() {

        event.preventDefault();
        var _token                  = $('#token').val();
        var array_detalle_producto  = $('#array_detalle_producto').val();
        var grupo                   = $('#grupo').val();
        var correlativo             = $('#correlativo').val();
        var fila                    = $(this).parents('.fila_pedido').attr('data_correlativo');
        var opcion_id               = $('#opcion').val();


        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-pedido-eliminar-fila",
            data    :   {
                            _token                      : _token,
                            array_detalle_producto      : array_detalle_producto,
                            grupo                       : grupo,
                            correlativo                 : correlativo,
                            fila                        : fila,
                            opcion_id                   : opcion_id,
                        },
            success: function (data) {
                alertajax("Eliminación exitosa");
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
        var opcion_id               = $('#opcion').val();

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
                            opcion_id                   : opcion_id,
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
        var opcion_id           = $('#opcion').val();

        /****** VALIDACIONES ********/
        /*if(cuenta_id.length<=0){
            alerterrorajax("Seleccione un cliente");
            return false;
        }*/

        abrircargando();
        $.ajax({
            
            type    :   "POST",
            url     :   carpeta+"/ajax-modal-lista-orden-cen-producto",
            data    :   {
                            _token          : _token,
                            cuenta_id       : cuenta_id,
                            opcion_id       : opcion_id
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
        var opcion_id               = $('#opcion').val();

        if(tabestado == 'prod'){

            var cuenta_id_m             = $('#cuenta_id_m').val();
            $('input[type=search]').val('').change();
            $("#despacholop").DataTable().search("").draw();
            data_producto = dataenviarproducto();
            if(data_producto.length<=0){alerterrorajax('Seleccione por lo menos una fila'); return false;}
            $('#modal-detalledocumento').niftyModal('hide');

            $.ajax({

                type    :   "POST",
                url     :   carpeta+"/ajax-modal-agregar-producto-pedido",
                data    :   {
                                _token                  : _token,
                                data_producto           : data_producto,
                                grupo                   : grupo,
                                correlativo             : correlativo,
                                array_detalle_producto  : array_detalle_producto,
                                cuenta_id_m             : cuenta_id_m,
                                opcion_id               : opcion_id,
                            },    
                success: function (data) {
                    cerrarcargando();
                    $('.lista_pedidos_despacho').html(data);
                },
                error: function (data) {
                    error500(data);
                }
            });


        }else{

            var tipo_grupo          = $('#tipo_grupo').val();

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
                                tipo_grupo              : tipo_grupo,
                                array_detalle_producto  : array_detalle_producto,
                                opcion_id               : opcion_id,
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







