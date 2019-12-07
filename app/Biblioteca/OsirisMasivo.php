<?php
namespace App\Biblioteca;
use DOMDocument;
use DB;
use Session;
use PDO;
use App\CMPContrato,App\STDEmpresaDireccion,App\CMPCategoria,App\CMPDocumentoCtble;
use App\ALMProducto,App\CMPDetalleProducto,App\WEBDetalleDocumentoAsociados;
USE App\WEBDocumentoNotaCredito,App\WEBDetallePedido,App\STDEmpresa,App\User,App\WEBDocumentoAsociados;

class OsirisMasivo{

	public $msjerror;
	public $orden_id;
	public $lote;

	public function __construct()
	{
	    $this->msjerror 	= '';
	    $this->orden_id 	= '';
	    $this->lote 		= '';
	}


        /************** GUARDAR ORDEN DE PEDIDO ****************/
        public function guardar_nota_credito($contrato_id,$direccion_id,$serie,$motivo_id,$glosa,$informacionadicional,$numero_documento,$funcion,$totalnotacredito,$documento_relacionado_id,$array_productos,$data_cod_orden_venta,$lote) {


                $vacio                                          =       '';
                $valor_cero                                     =       '0';
                $fecha_ilimitada                                =       date_format(date_create('1901-01-01'), 'Y-m-d');
                $accion                                         =       'I';

                $contrato                                       =       CMPContrato::where('COD_CONTRATO','=',$contrato_id)->first();
                $motivo                                         =       CMPCategoria::where('COD_CATEGORIA','=',$motivo_id)->first();
                $empresdireccion                                =       STDEmpresaDireccion::where('COD_EMPR','=',Session::get('empresas')->COD_EMPR)
                                                                        ->where('COD_ESTADO','=',1)
                                                                        ->where('IND_DIRECCION_FISCAL','=',1)->first();

                $documento_contable                             =       CMPDocumentoCtble::where('COD_DOCUMENTO_CTBLE','=',$documento_relacionado_id)->first();

                $serie                                          =       (string)$serie;
                $nrodoc                                         =       (string)$numero_documento;
                $txt_info_adicional                             =       (string)$informacionadicional;

                $cod_empr_receptor                              =       (string)$contrato->COD_EMPR_CLIENTE;     //'IACHEM0000000513'; //REQUEST
                $nom_empr_receptor                              =       (string)$contrato->TXT_EMPR_CLIENTE;     //'HIPERMERCADOS TOTTUS S.A.'; //REQUEST
                $cod_contrato_receptor                          =       (string)$contrato->COD_CONTRATO;          //'IILMRC0000000795'; //REQUEST


                $cod_cultivo_origen                             =       'CCU0000000000001';
                
                $fecha_emision                                  =       $funcion->fin; //'2019-08-09 00:00:00';
                $fecha_gracia                                   =       $funcion->messiguiente;
                $fecha_vencimiento                              =       $funcion->messiguiente;
                $ind_material_servicio                          =       'M';

                $cod_categoria_tipo_doc                         =       'TDO0000000000007';
                $nom_categoria_tipo_doc                         =       'NOTA DE CREDITO';
                $moneda_id                                      =       'MON0000000000001';
                $moneda_nombre                                  =       'SOLES';

                $ind_compra_venta                               =       'V';
                $operador                                       =       '1';
                $cod_categoria_modulo                           =       'MSI0000000000010';
                $cod_categoria_motivo_emision                   =       (string)$motivo_id;
                $txt_categoria_motivo_emision                   =       (string)$motivo->NOM_CATEGORIA;

                $cod_categoria_estado_doc_ctble                 =       'EDC0000000000001';
                $txt_categoria_estado_doc_ctble                 =       'GENERADO';
                $cod_categoria_tipo_pago                        =       'TIP0000000000005';
                $txt_categoria_tipo_pago                        =       'CREDITO A 30 DÍAS';


                $can_tipo_cambio                                =       $funcion->funciones->tipo_cambio()->CAN_VENTA;   //CAMBIAR FRANK
                $can_impuesto_vta                               =       $valor_cero;
                $can_impuesto_renta                             =       $valor_cero;
                $can_sub_total                                  =       $totalnotacredito;
                $can_total                                      =       $totalnotacredito;
                $txt_nro_pedido                                 =       (string)$documento_contable->TXT_NRO_PEDIDO;//REQUEST
                $ind_notificacion_cliente                       =       'False,';

                $txt_glosa                                      =       $glosa.' / lote web '.$lote;//'// TOTTU TRUJILLO / 3997 / 3998,';
                $cod_estado                                     =       1;
                $cod_usuario_registro                           =       Session::get('usuario')->name;


                $cod_tipo_documento_asoc_elec                   =       'TDO0000000000001';
                $ind_electronico                                =       1;


                $cod_empr                                       =       Session::get('empresas')->COD_EMPR;
                $nom_empr                                       =       Session::get('empresas')->NOM_EMPR;
                $cod_centro                                     =       Session::get('centros')->COD_CENTRO;

                $cod_direccion_emisor                           =       (string)$empresdireccion->COD_DIRECCION;//@COD_DIRECCION_EMISOR='ISRJDI0000000802',
                $cod_direccion_receptor                         =       (string)$direccion_id;//@COD_DIRECCION_EMISOR='ISRJDI0000000802',
                $cod_direccion_destino                          =       (string)$direccion_id;//@COD_DIRECCION_EMISOR='ISRJDI0000000802',




                $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.DOCUMENTO_CTBLE_IUD ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');


                $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@IND_TIPO_OPERACION='I',
                $stmt->bindParam(2, $vacio  ,PDO::PARAM_STR);                                   //@COD_DOCUMENTO_CTBLE='',
                $stmt->bindParam(3, $serie ,PDO::PARAM_STR);                                    //@NRO_SERIE='F005',
                $stmt->bindParam(4, $nrodoc ,PDO::PARAM_STR);                                   //@NRO_DOC='00000420',
                $stmt->bindParam(5, $cod_empr ,PDO::PARAM_STR);                                 //@COD_EMPR='IACHEM0000010394',
                $stmt->bindParam(6, $cod_centro  ,PDO::PARAM_STR);                              //@COD_CENTRO='CEN0000000000002'
                $stmt->bindParam(7, $cod_empr  ,PDO::PARAM_STR);                                //@COD_EMPR_EMISOR='IACHEM0000010394',
                $stmt->bindParam(8, $nom_empr  ,PDO::PARAM_STR);                                //@TXT_EMPR_EMISOR='INDUAMERICA INTERNACIONAL S.A.C.',
                $stmt->bindParam(9, $cod_empr_receptor  ,PDO::PARAM_STR);                       //@COD_EMPR_RECEPTOR='IACHEM0000000513',
                $stmt->bindParam(10, $nom_empr_receptor  ,PDO::PARAM_STR);                      //@TXT_EMPR_RECEPTOR='HIPERMERCADOS TOTTUS S.A.',

                $stmt->bindParam(11, $vacio  ,PDO::PARAM_STR);                                  //@COD_EMPR_IMPRESION='',
                $stmt->bindParam(12, $vacio ,PDO::PARAM_STR);                                   //@TXT_EMPR_IMPRESION='',
                $stmt->bindParam(13, $vacio  ,PDO::PARAM_STR);                                  //@COD_EMPR_ORIGEN='',
                $stmt->bindParam(14, $vacio  ,PDO::PARAM_STR);                                  //@TXT_EMPR_ORIGEN='',
                $stmt->bindParam(15, $vacio ,PDO::PARAM_STR);                                   //@COD_EMPR_DESTINO='', 
                $stmt->bindParam(16, $vacio ,PDO::PARAM_STR);                                   //@TXT_EMPR_DESTINO='', 
                $stmt->bindParam(17, $vacio ,PDO::PARAM_STR);                                   //@COD_EMPR_BANCO='',
                $stmt->bindParam(18, $vacio ,PDO::PARAM_STR);                                   //@TXT_EMPR_BANCO='',
                $stmt->bindParam(19, $cod_categoria_tipo_doc ,PDO::PARAM_STR);                  //@COD_CATEGORIA_TIPO_DOC='TDO0000000000007',
                $stmt->bindParam(20, $nom_categoria_tipo_doc ,PDO::PARAM_STR);                  //@TXT_CATEGORIA_TIPO_DOC='NOTA DE CREDITO',

                $stmt->bindParam(21, $vacio ,PDO::PARAM_STR);                                   //@COD_CATEGORIA_MOTIVO_TRASLADO='',
                $stmt->bindParam(22, $vacio ,PDO::PARAM_STR);                                   //@TXT_CATEGORIA_MOTIVO_TRASLADO='',
                $stmt->bindParam(23, $moneda_id ,PDO::PARAM_STR);                               //@COD_CATEGORIA_MONEDA='MON0000000000001',
                $stmt->bindParam(24, $moneda_nombre ,PDO::PARAM_STR);                           //@TXT_CATEGORIA_MONEDA='SOLES',
                $stmt->bindParam(25, $vacio ,PDO::PARAM_STR);                                   //@COD_CHOFER='',
                $stmt->bindParam(26, $vacio ,PDO::PARAM_STR);                                   //@TXT_CHOFER='',
                $stmt->bindParam(27, $cod_direccion_emisor  ,PDO::PARAM_STR);                   //@COD_DIRECCION_EMISOR='ISRJDI0000000802',
                $stmt->bindParam(28, $cod_direccion_receptor ,PDO::PARAM_STR);                  //@COD_DIRECCION_RECEPTOR='IACHDI0000000445',
                $stmt->bindParam(29, $vacio ,PDO::PARAM_STR);                                   //@COD_DIRECCION_ORIGEN='',
                $stmt->bindParam(30, $cod_direccion_destino  ,PDO::PARAM_STR);                  //@COD_DIRECCION_DESTINO='IACHDI0000000445',

                $stmt->bindParam(31, $vacio ,PDO::PARAM_STR);                                   //@COD_DIRECCION_IMPRESION='',
                $stmt->bindParam(32, $vacio ,PDO::PARAM_STR);                                   //@COD_CONTRATO_EMISOR='',
                $stmt->bindParam(33, $vacio ,PDO::PARAM_STR);                                   //@COD_CULTIVO_EMISOR='',
                $stmt->bindParam(34, $cod_contrato_receptor ,PDO::PARAM_STR);                   //@COD_CONTRATO_RECEPTOR='IILMRC0000000795',
                $stmt->bindParam(35, $cod_cultivo_origen  ,PDO::PARAM_STR);                     //@COD_CULTIVO_RECEPTOR='CCU0000000000001',
                $stmt->bindParam(36, $vacio  ,PDO::PARAM_STR);                                  //@COD_CONTRATO_ORIGEN='',
                $stmt->bindParam(37, $vacio  ,PDO::PARAM_STR);                                  //@COD_CULTIVO_ORIGEN='',
                $stmt->bindParam(38, $vacio  ,PDO::PARAM_STR);                                  //@COD_CONTRATO_DESTINO='',
                $stmt->bindParam(39, $vacio  ,PDO::PARAM_STR);                                  //@COD_CULTIVO_DESTINO='',
                $stmt->bindParam(40, $fecha_emision  ,PDO::PARAM_STR);                          //@FEC_EMISION='2019-08-09 00:00:00',

                $stmt->bindParam(41, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_RECEPCION='1901-01-01 00:00:00',
                $stmt->bindParam(42, $fecha_gracia  ,PDO::PARAM_STR);                            //@FEC_GRACIA='2019-09-08 00:00:00',
                $stmt->bindParam(43, $fecha_vencimiento  ,PDO::PARAM_STR);                       //@FEC_VENCIMIENTO='2019-09-08 00:00:00',
                $stmt->bindParam(44, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_ENTRADA_PLANTA='1901-01-01 00:00:00',
                $stmt->bindParam(45, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_SALIDA_PLANTA='1901-01-01 00:00:00',
                $stmt->bindParam(46, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_LLEGADA_PLANTA='1901-01-01 00:00:00', 
                $stmt->bindParam(47, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_SALIDA_DESTINO='1901-01-01 00:00:00',
                $stmt->bindParam(48, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_LLEGADA_DESTINO='1901-01-01 00:00:00',
                $stmt->bindParam(49, $fecha_ilimitada  ,PDO::PARAM_STR);                         //@FEC_TERMINO='1901-01-01 00:00:00',
                $stmt->bindParam(50, $ind_material_servicio  ,PDO::PARAM_STR);                   //@IND_MATERIAL_SERVICIO='M',

                $stmt->bindParam(51, $ind_compra_venta  ,PDO::PARAM_STR);                       //@IND_COMPRA_VENTA='V', 
                $stmt->bindParam(52, $operador  ,PDO::PARAM_STR);                               //@OPERADOR=1,
                $stmt->bindParam(53, $cod_categoria_modulo  ,PDO::PARAM_STR);                   //@COD_CATEGORIA_MODULO='MSI0000000000010',
                $stmt->bindParam(54, $vacio ,PDO::PARAM_STR);                                   //@COD_CATEGORIA_CONDICION_PAGO='',
                $stmt->bindParam(55, $vacio ,PDO::PARAM_STR);                                   //@TXT_CATEGORIA_CONDICION_PAGO='',
                $stmt->bindParam(56, $cod_categoria_motivo_emision  ,PDO::PARAM_STR);           //@COD_CATEGORIA_MOTIVO_EMISION='MEM0000000000015',
                $stmt->bindParam(57, $txt_categoria_motivo_emision  ,PDO::PARAM_STR);           //@TXT_CATEGORIA_MOTIVO_EMISION='DESCUENTO POR ITEM',
                $stmt->bindParam(58, $cod_categoria_estado_doc_ctble  ,PDO::PARAM_STR);         //@COD_CATEGORIA_ESTADO_DOC_CTBLE='EDC0000000000001',
                $stmt->bindParam(59, $txt_categoria_estado_doc_ctble  ,PDO::PARAM_STR);         //@TXT_CATEGORIA_ESTADO_DOC_CTBLE='GENERADO',
                $stmt->bindParam(60, $cod_categoria_tipo_pago  ,PDO::PARAM_STR);                //@COD_CATEGORIA_TIPO_PAGO='TIP0000000000005',

                $stmt->bindParam(61, $txt_categoria_tipo_pago  ,PDO::PARAM_STR);                //@TXT_CATEGORIA_TIPO_PAGO='CREDITO A 30 DÍAS', 
                $stmt->bindParam(62, $vacio  ,PDO::PARAM_STR);                                  //@COD_CONCEPTO_CENTRO_COSTO='',
                $stmt->bindParam(63, $vacio  ,PDO::PARAM_STR);                                  //@TXT_CONCEPTO_CENTRO_COSTO='',
                $stmt->bindParam(64, $vacio  ,PDO::PARAM_STR);                                  //@COD_VEHICULO='',
                $stmt->bindParam(65, $vacio  ,PDO::PARAM_STR);                                  //@COD_VEHICULO_NO_MOTRIZ='',
                $stmt->bindParam(66, $can_tipo_cambio  ,PDO::PARAM_STR);                        //@CAN_TIPO_CAMBIO=3.2970, 
                $stmt->bindParam(67, $can_impuesto_vta  ,PDO::PARAM_STR);                       //@CAN_IMPUESTO_VTA='',
                $stmt->bindParam(68, $can_impuesto_renta  ,PDO::PARAM_STR);                     //@CAN_IMPUESTO_RENTA='',
                $stmt->bindParam(69, $can_sub_total  ,PDO::PARAM_STR);                          //@CAN_SUB_TOTAL=35607.3200,
                $stmt->bindParam(70, $can_total ,PDO::PARAM_STR);                               //@CAN_TOTAL=35607.3200,

                $stmt->bindParam(71, $valor_cero ,PDO::PARAM_STR);                              //@CAN_COMISION=0, 
                $stmt->bindParam(72, $valor_cero ,PDO::PARAM_STR);                              //@CAN_COSTO_FLETE=0,
                $stmt->bindParam(73, $valor_cero ,PDO::PARAM_STR);                              //@CAN_COSTO_ESTIBA=0,
                $stmt->bindParam(74, $valor_cero ,PDO::PARAM_STR);                              //@CAN_ADELANTO_CUENTA=0,
                $stmt->bindParam(75, $valor_cero ,PDO::PARAM_STR);                              //@CAN_RETENCION=0,
                $stmt->bindParam(76, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PERCEPCION=0, 
                $stmt->bindParam(77, $valor_cero ,PDO::PARAM_STR);                              //@CAN_DETRACCION=0,
                $stmt->bindParam(78, $valor_cero ,PDO::PARAM_STR);                              //@CAN_DCTO=0,
                $stmt->bindParam(79, $valor_cero ,PDO::PARAM_STR);                              //@CAN_NETO_PAGAR=0,
                $stmt->bindParam(80, $valor_cero ,PDO::PARAM_STR);                              //@CAN_IMPORTE_DETRAER=0,

                $stmt->bindParam(81, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_SALDO=0, 
                $stmt->bindParam(82, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_PESO=0,
                $stmt->bindParam(83, $vacio  ,PDO::PARAM_STR);                                  //@NRO_ITT='',
                $stmt->bindParam(84, $valor_cero ,PDO::PARAM_STR);                              //@NRO_CPM='',
                $stmt->bindParam(85, $vacio  ,PDO::PARAM_STR);                                  //@TXT_NRO_DETRACCION=' ',
                $stmt->bindParam(86, $vacio  ,PDO::PARAM_STR);                                  //@COD_PAGO_SEGUN='', 
                $stmt->bindParam(87, $vacio  ,PDO::PARAM_STR);                                  //@COD_CLIENTE_REFERENCIA='',
                $stmt->bindParam(88, $txt_nro_pedido  ,PDO::PARAM_STR);                         //@TXT_NRO_PEDIDO='228164456',
                $stmt->bindParam(89, $vacio  ,PDO::PARAM_STR);                                  //@COD_ENVIAR_A='',
                $stmt->bindParam(90, $vacio  ,PDO::PARAM_STR);                                  //@COD_SERVICIO_GASTO='',

                $stmt->bindParam(91, $vacio  ,PDO::PARAM_STR);                                  //@NOM_SERVICIO_GASTO='',
                $stmt->bindParam(92, $vacio  ,PDO::PARAM_STR);                                  //@NRO_OPERACIONES_CAJA='',
                $stmt->bindParam(93, $txt_glosa  ,PDO::PARAM_STR);                              //@TXT_GLOSA='// TOTTU TRUJILLO / 3997 / 3998',
                $stmt->bindParam(94, $vacio  ,PDO::PARAM_STR);                                  //@TXT_TIPO_REFERENCIA='',
                $stmt->bindParam(95, $vacio  ,PDO::PARAM_STR);                                  //@TXT_REFERENCIA='',     
                $stmt->bindParam(96, $cod_estado  ,PDO::PARAM_STR);                             //@COD_ESTADO=1, 
                $stmt->bindParam(97, $cod_usuario_registro  ,PDO::PARAM_STR);                   //@COD_USUARIO_REGISTRO='PHORNALL',
                $stmt->bindParam(98, $vacio  ,PDO::PARAM_STR);                                  //@COD_PERIODO='',
                $stmt->bindParam(99, $vacio  ,PDO::PARAM_STR);                                  //@COD_CUENTA_CONTABLE='',
                $stmt->bindParam(100, $valor_cero ,PDO::PARAM_STR);                             //@CAN_NO_GRAVADAS=0,

                $stmt->bindParam(101, $vacio ,PDO::PARAM_STR);                                  //@COD_EMPR_TRANS='',
                $stmt->bindParam(102, $vacio  ,PDO::PARAM_STR);                                 //@TXT_EMPR_TRANS='',
                $stmt->bindParam(103, $valor_cero  ,PDO::PARAM_STR);                            //@IND_ENTREGADO=0,
                $stmt->bindParam(104, $vacio  ,PDO::PARAM_STR);                                 //@TXT_ENTREGADO='',
                $stmt->bindParam(105, $valor_cero  ,PDO::PARAM_STR);                            //@IND_RECP_ALTERNO=0,
                $stmt->bindParam(106, $valor_cero  ,PDO::PARAM_STR);                            //@IND_EXTORNO=0, 
                $stmt->bindParam(107, $vacio  ,PDO::PARAM_STR);                                 //@NRO_CTA_CTBLE='',
                $stmt->bindParam(108, $vacio  ,PDO::PARAM_STR);                                 //@TXT_ORIGEN='',
                $stmt->bindParam(109, $vacio ,PDO::PARAM_STR);                                  //@COD_CTA_GASTO_FUNCION='',
                $stmt->bindParam(110, $vacio ,PDO::PARAM_STR);                                  //@NRO_CTA_GASTO_FUNCION='',

                $stmt->bindParam(111, $valor_cero  ,PDO::PARAM_STR);                            //@IND_SUSTENTO=0, 
                $stmt->bindParam(112, $cod_tipo_documento_asoc_elec  ,PDO::PARAM_STR);          //@COD_TIPO_DOCUMENTO_ASOC_ELEC='TDO0000000000001',
                $stmt->bindParam(113, $valor_cero ,PDO::PARAM_STR);                             //@IND_ENVIADO_ELEC=0,
                $stmt->bindParam(114, $vacio ,PDO::PARAM_STR);                                  //@NRO_SERIE_ELEC='',
                $stmt->bindParam(115, $ind_electronico  ,PDO::PARAM_STR);                       //@IND_ELECTRONICO=1,
                $stmt->bindParam(116, $valor_cero  ,PDO::PARAM_STR);                            //@IND_AFECTO_IVAP=0, 
                $stmt->bindParam(117, $vacio ,PDO::PARAM_STR);                                  //@COD_CATEGORIA_SELLO='',
                $stmt->bindParam(118, $txt_info_adicional  ,PDO::PARAM_STR);                    //@TXT_INFO_ADICIONAL='',
                $stmt->bindParam(119, $valor_cero  ,PDO::PARAM_STR);                            //@IND_GEN_AUTO=0,
                $stmt->bindParam(120, $vacio  ,PDO::PARAM_STR);                                 //@COD_CATEGORIA_REG_CTBLE='',

                $stmt->bindParam(121, $vacio  ,PDO::PARAM_STR);                                 //@COD_FLUJO_CAJA='', 
                $stmt->bindParam(122, $vacio  ,PDO::PARAM_STR);                                 //@COD_ITEM_MOVIMIENTO='',
                $stmt->bindParam(123, $vacio ,PDO::PARAM_STR);                                  //@ESTADO_ELEC='',
                $stmt->bindParam(124, $fecha_ilimitada ,PDO::PARAM_STR);                        //@FEC_DETRAC='1901-01-01 00:00:00',
                $stmt->bindParam(125, $cod_empr_receptor  ,PDO::PARAM_STR);                     //@COD_EMPR_DOC='IACHEM0000000513',
                $stmt->bindParam(126, $nom_empr_receptor  ,PDO::PARAM_STR);                     //@TXT_EMPR_DOC='HIPERMERCADOS TOTTUS S.A.', 
                $stmt->bindParam(127, $cod_contrato_receptor ,PDO::PARAM_STR);                  //@COD_CONTRATO_DOC='IILMRC0000000795',
                $stmt->bindParam(128, $cod_cultivo_origen  ,PDO::PARAM_STR);                    //@COD_CULTIVO_DOC='CCU0000000000001',
                $stmt->bindParam(129, $cod_direccion_receptor  ,PDO::PARAM_STR);                //@COD_DIRECCION_DOC='IACHDI0000000445',
                $stmt->bindParam(130, $vacio  ,PDO::PARAM_STR);                                 //@COD_DIRECCION_EMPR_SIST='',


                $stmt->bindParam(131, $vacio  ,PDO::PARAM_STR);                                 //@TXT_ORDEN_DEVOLUCION='', 
                $stmt->bindParam(132, $vacio  ,PDO::PARAM_STR);                                 //@COD_EMPR_ALTERNATIVA='',
                $stmt->bindParam(133, $vacio ,PDO::PARAM_STR);                                  //@TXT_EMPR_ALTERNATIVA='',
                $stmt->bindParam(134, $vacio ,PDO::PARAM_STR);                                  //@COD_CONTRATO_ALTERNATIVA='',
                $stmt->bindParam(135, $vacio  ,PDO::PARAM_STR);                                 //@COD_CULTIVO_ALTERNATIVA='',
                $stmt->bindParam(136, $vacio  ,PDO::PARAM_STR);                                 //@COD_DIRECCION_ALTERNATIVA='', 
                $stmt->bindParam(137, $vacio ,PDO::PARAM_STR);                                  //@TXT_DIRECCION_ALTERNATIVA='',
                $stmt->bindParam(138, $vacio  ,PDO::PARAM_STR);                                 //@COD_MOTIVO_EXTORNO='',
                $stmt->bindParam(139, $vacio  ,PDO::PARAM_STR);                                 //@GLOSA_EXTORNO='',
                $stmt->bindParam(140, $vacio  ,PDO::PARAM_STR);                                 //@COD_TIPO_LIQUIDACION='',

                $stmt->bindParam(141, $vacio  ,PDO::PARAM_STR);                                 //@TXT_TIPO_LIQUIDACION='', 
                $stmt->bindParam(142, $ind_notificacion_cliente  ,PDO::PARAM_STR);              //@IND_NOTIFICACION_CLIENTE='False',
                $stmt->bindParam(143, $valor_cero  ,PDO::PARAM_STR);                            //@IND_GRATUITO=0,
                $stmt->bindParam(144, $valor_cero  ,PDO::PARAM_STR);                            //@IND_EXPORTACION=0,

                $stmt->execute();
                $coddocumento = $stmt->fetch();




                /****************** REGISTRO DE CABECERA *********************/

                $codigo                         =       $funcion->funciones->generar_codigo('WEB.documento_nota_credito',6);
                $idcabecera                     =       $funcion->funciones->getCreateIdMaestra('WEB.documento_nota_credito');
                $total_factura                  =       0.0000;
                $total_reglas                   =       0.0000;
                $fechaactual                    =       date('d-m-Y H:i:s');

                $cabecera                       =       new WEBDocumentoNotaCredito;
                $cabecera->id                   =       $idcabecera;
                $cabecera->contrato_id          =       $contrato_id;
                $cabecera->nota_credito_id      =       $coddocumento[0];
                $cabecera->estado               =       'CE';
                $cabecera->codigo               =       $codigo;
                $cabecera->total_factura        =       $total_factura;
                $cabecera->total_reglas         =       $total_reglas;
                $cabecera->total_notacredito    =       $totalnotacredito;
                $cabecera->fecha_crea           =       $fechaactual;
                $cabecera->usuario_crea         =       Session::get('usuario')->id;
                $cabecera->empresa_id           =       Session::get('empresas')->COD_EMPR;
                $cabecera->centro_id            =       Session::get('centros')->COD_CENTRO;
                $cabecera->lote                 =       $lote;
                $cabecera->txt_modulo           =       'BOLETAS_MASIVAS';
                $cabecera->save();



                $iddetalle                                       =       $funcion->funciones->getCreateIdMaestra('WEB.documento_asociados');

                $asociado                                        =       new WEBDocumentoAsociados;
                $asociado->id                                    =       $iddetalle;
                $asociado->total_factura                         =       $totalnotacredito;
                $asociado->total_reglas                          =       $total_reglas;
                $asociado->fecha_crea                            =       $fechaactual;
                $asociado->documento_nota_credito_id             =       $idcabecera;
                $asociado->orden_id                              =       $data_cod_orden_venta;
                $asociado->documento_id                          =       $documento_relacionado_id;
                $asociado->usuario_crea                          =       Session::get('usuario')->id;
                $asociado->empresa_id                            =       Session::get('empresas')->COD_EMPR;
                $asociado->centro_id                             =       Session::get('centros')->COD_CENTRO;
                $asociado->save();




                foreach($array_productos as $index => $item){

                        $precio                                 =       $item->precio;
                        $total                                  =       $item->precio*$item->cantidad;
                        $producto                               =       ALMProducto::where('COD_PRODUCTO','=',$item->producto_id)->first();


                        $COD_PRODUCTO                           =       $producto->COD_PRODUCTO;
                        $COD_LOTE                               =       '0000000000000000';
                        $NRO_LINEA                              =       (string)($index+1);
                        $TXT_NOMBRE_PRODUCTO                    =       $producto->NOM_PRODUCTO;
                        $CAN_PRODUCTO                           =       (string)($total/$precio);
                        $CAN_PESO                               =       (string)($producto->CAN_PESO_MATERIAL*$CAN_PRODUCTO);
                        $CAN_PESO_PRODUCTO                      =       (string)$producto->CAN_PESO_MATERIAL;

                        $CAN_TASA_IGV                           =       '0.1800';
                        $CAN_PRECIO_UNIT_IGV                    =       (string)$precio;
                        $CAN_PRECIO_UNIT                        =       (string)$precio;
                        $CAN_PRECIO_COSTO                       =       (string)$precio;
                        $CAN_VALOR_VTA                          =       (string)$total;
                        $CAN_VALOR_VENTA_IGV                    =       (string)$total;
                        $CAN_PENDIENTE                          =       (string)$CAN_PRODUCTO;
                        $IND_MATERIAL_SERVICIO                  =       'M';
                        $COD_CONCEPTO_CENTRO_COSTO              =       'IICHCC0000000002';
                        $TXT_CONCEPTO_CENTRO_COSTO              =       'ACOPIO';
                        $COD_ESTADO                             =       '1';
                        $COD_USUARIO_REGISTRO                   =       Session::get('usuario')->name;


                        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.DETALLE_PRODUCTO_IUD ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?');
                        $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@IND_TIPO_OPERACION='I',
                        $stmt->bindParam(2, $coddocumento[0]  ,PDO::PARAM_STR);                         //@COD_TABLA='IILMVR0000002923',
                        $stmt->bindParam(3, $COD_PRODUCTO ,PDO::PARAM_STR);                             //@COD_PRODUCTO='PRD0000000016186',
                        $stmt->bindParam(4, $COD_LOTE ,PDO::PARAM_STR);                                 //@COD_LOTE='0000000000000000', 
                        $stmt->bindParam(5, $NRO_LINEA ,PDO::PARAM_STR);                                //@NRO_LINEA=1, 
                        $stmt->bindParam(6, $TXT_NOMBRE_PRODUCTO  ,PDO::PARAM_STR);                     //@TXT_NOMBRE_PRODUCTO='ARROCILLO DE ARROZ AÑEJO X 50 KG',
                        $stmt->bindParam(7, $vacio  ,PDO::PARAM_STR);                                   //@TXT_DETALLE_PRODUCTO='',
                        $stmt->bindParam(8, $CAN_PRODUCTO  ,PDO::PARAM_STR);                            //@CAN_PRODUCTO=1.0000,
                        $stmt->bindParam(9, $valor_cero  ,PDO::PARAM_STR);                              //@CAN_PRODUCTO_ENVIADO=0,
                        $stmt->bindParam(10, $CAN_PESO  ,PDO::PARAM_STR);                               //@CAN_PESO=50.0000,

                        $stmt->bindParam(11, $CAN_PESO_PRODUCTO ,PDO::PARAM_STR);                       //@CAN_PESO_PRODUCTO=50.0000,
                        $stmt->bindParam(12, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_PESO_ENVIADO=0,
                        $stmt->bindParam(13, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PESO_INGRESO=0, 
                        $stmt->bindParam(14, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PESO_SALIDA=0, 
                        $stmt->bindParam(15, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PESO_BRUTO=0, 
                        $stmt->bindParam(16, $valor_cero  ,PDO::PARAM_STR);                             //@CAM_PESO_TARA=0,
                        $stmt->bindParam(17, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_PESO_NETO=0,
                        $stmt->bindParam(18, $CAN_TASA_IGV  ,PDO::PARAM_STR);                           //@CAN_TASA_IGV=0.1800,
                        $stmt->bindParam(19, $CAN_PRECIO_UNIT_IGV  ,PDO::PARAM_STR);                    //@CAN_PRECIO_UNIT_IGV=2.0000,
                        $stmt->bindParam(20, $CAN_PRECIO_UNIT  ,PDO::PARAM_STR);                        //@CAN_PRECIO_UNIT=2.0000,

                        $stmt->bindParam(21, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PRECIO_ORIGEN=0,
                        $stmt->bindParam(22, $CAN_PRECIO_COSTO  ,PDO::PARAM_STR);                       //@CAN_PRECIO_COSTO=2.0000,
                        $stmt->bindParam(23, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PRECIO_BRUTO=0, 
                        $stmt->bindParam(24, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PRECIO_KILOS=0,
                        $stmt->bindParam(25, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PRECIO_SACOS=0, 
                        $stmt->bindParam(26, $CAN_VALOR_VTA  ,PDO::PARAM_STR);                          //@CAN_VALOR_VTA=2.0000, 
                        $stmt->bindParam(27, $CAN_VALOR_VENTA_IGV  ,PDO::PARAM_STR);                    //@CAN_VALOR_VENTA_IGV=2.0000,
                        $stmt->bindParam(28, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_KILOS=0,
                        $stmt->bindParam(29, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_SACOS=0,
                        $stmt->bindParam(30, $CAN_PENDIENTE  ,PDO::PARAM_STR);                          //@CAN_PENDIENTE=1.0000,

                        $stmt->bindParam(31, $valor_cero ,PDO::PARAM_STR);                              //@CAN_PORCENTAJE_DESCUENTO=0,
                        $stmt->bindParam(32, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_DESCUENTO=0,
                        $stmt->bindParam(33, $valor_cero ,PDO::PARAM_STR);                              //@CAN_ADELANTO=0, 
                        $stmt->bindParam(34, $vacio ,PDO::PARAM_STR);                                   //@TXT_DESCRIPCION='', 
                        $stmt->bindParam(35, $IND_MATERIAL_SERVICIO ,PDO::PARAM_STR);                   //@IND_MATERIAL_SERVICIO='M' 
                        $stmt->bindParam(36, $valor_cero  ,PDO::PARAM_STR);                             //@IND_IGV=0, 
                        $stmt->bindParam(37, $vacio  ,PDO::PARAM_STR);                                  //@COD_ALMACEN='',
                        $stmt->bindParam(38, $vacio  ,PDO::PARAM_STR);                                  //@TXT_ALMACEN='',
                        $stmt->bindParam(39, $vacio  ,PDO::PARAM_STR);                                  //@COD_OPERACION='',
                        $stmt->bindParam(40, $vacio  ,PDO::PARAM_STR);                                  //@COD_OPERACION_AUX='',

                        $stmt->bindParam(41, $vacio ,PDO::PARAM_STR);                                   //@COD_EMPR_SERV='',
                        $stmt->bindParam(42, $vacio  ,PDO::PARAM_STR);                                  //@TXT_EMPR_SERV='',
                        $stmt->bindParam(43, $vacio ,PDO::PARAM_STR);                                   //@NRO_CONTRATO_SERV='', 
                        $stmt->bindParam(44, $vacio ,PDO::PARAM_STR);                                   //@NRO_CONTRATO_CULTIVO_SERV='', 
                        $stmt->bindParam(45, $vacio ,PDO::PARAM_STR);                                   //@NRO_HABILITACION_SERV='', 
                        $stmt->bindParam(46, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_PRECIO_EMPR_SERV=0, 
                        $stmt->bindParam(47, $vacio  ,PDO::PARAM_STR);                                  //@NRO_CONTRATO_GRUPO='',
                        $stmt->bindParam(48, $vacio  ,PDO::PARAM_STR);                                  //@NRO_CONTRATO_CULTIVO_GRUPO='',
                        $stmt->bindParam(49, $vacio  ,PDO::PARAM_STR);                                  //@NRO_HABILITACION_GRUPO='',
                        $stmt->bindParam(50, $vacio  ,PDO::PARAM_STR);                                  //@COD_CATEGORIA_TIPO_PAGO='',

                        $stmt->bindParam(51, $vacio ,PDO::PARAM_STR);                                   //@COD_USUARIO_INGRESO='',
                        $stmt->bindParam(52, $vacio  ,PDO::PARAM_STR);                                  //@COD_USUARIO_SALIDA='',
                        $stmt->bindParam(53, $vacio ,PDO::PARAM_STR);                                   //@TXT_GLOSA_PESO_IN='', 
                        $stmt->bindParam(54, $vacio ,PDO::PARAM_STR);                                   //@TXT_GLOSA_PESO_OUT='', 
                        $stmt->bindParam(55, $COD_CONCEPTO_CENTRO_COSTO ,PDO::PARAM_STR);               //@COD_CONCEPTO_CENTRO_COSTO='IICHCC0000000002', 
                        $stmt->bindParam(56, $TXT_CONCEPTO_CENTRO_COSTO  ,PDO::PARAM_STR);              //@TXT_CONCEPTO_CENTRO_COSTO='ACOPIO', 
                        $stmt->bindParam(57, $vacio  ,PDO::PARAM_STR);                                  //@TXT_REFERENCIA='',
                        $stmt->bindParam(58, $vacio  ,PDO::PARAM_STR);                                  //@TXT_TIPO_REFERENCIA='',
                        $stmt->bindParam(59, $vacio  ,PDO::PARAM_STR);                                  //@IND_COSTO_ARBITRARIO='',
                        $stmt->bindParam(60, $COD_ESTADO  ,PDO::PARAM_STR);                             //@COD_ESTADO=1,


                        $stmt->bindParam(61, $COD_USUARIO_REGISTRO ,PDO::PARAM_STR);                     //@COD_USUARIO_REGISTRO='PHORNALL',
                        $stmt->bindParam(62, $vacio  ,PDO::PARAM_STR);                                  //@COD_TIPO_ESTADO='',
                        $stmt->bindParam(63, $vacio ,PDO::PARAM_STR);                                   //@TXT_TIPO_ESTADO='', 
                        $stmt->bindParam(64, $vacio ,PDO::PARAM_STR);                                   //@TXT_GLOSA_ASIENTO='', 
                        $stmt->bindParam(65, $vacio ,PDO::PARAM_STR);                                   //@TXT_CUENTA_CONTABLE='', 
                        $stmt->bindParam(66, $vacio  ,PDO::PARAM_STR);                                  //@COD_ASIENTO_PROVISION='',
                        $stmt->bindParam(67, $vacio  ,PDO::PARAM_STR);                                  //@COD_ASIENTO_EXTORNO='',
                        $stmt->bindParam(68, $vacio  ,PDO::PARAM_STR);                                  //@COD_ASIENTO_CANJE='',
                        $stmt->bindParam(69, $vacio  ,PDO::PARAM_STR);                                  //@COD_TIPO_DOCUMENTO='',
                        $stmt->bindParam(70, $vacio  ,PDO::PARAM_STR);                                  //@COD_DOCUMENTO_CTBLE='',

                        $stmt->bindParam(71, $vacio ,PDO::PARAM_STR);                                   //@TXT_SERIE_DOCUMENTO='',
                        $stmt->bindParam(72, $vacio  ,PDO::PARAM_STR);                                  //@TXT_NUMERO_DOCUMENTO='',
                        $stmt->bindParam(73, $vacio ,PDO::PARAM_STR);                                   //@COD_GASTO_FUNCION='', 
                        $stmt->bindParam(74, $vacio ,PDO::PARAM_STR);                                   //@COD_CENTRO_COSTO='', 
                        $stmt->bindParam(75, $vacio ,PDO::PARAM_STR);                                   //@COD_ORDEN_COMPRA='', 
                        $stmt->bindParam(76, $fecha_ilimitada  ,PDO::PARAM_STR);                        //@FEC_FECHA_SERV='1901-01-01', 
                        $stmt->bindParam(77, $vacio  ,PDO::PARAM_STR);                                  //@COD_CATEGORIA_TIPO_SERV_ORDEN='',
                        $stmt->bindParam(78, $vacio  ,PDO::PARAM_STR);                                  //@IND_GASTO_COSTO=' ',
                        $stmt->bindParam(79, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_PORCENTAJE_PERCEPCION=0,
                        $stmt->bindParam(80, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_VALOR_PERCEPCION=0
                        $stmt->execute();


                        $regla_id                             =       '1CIX00000001';//es referencial
                        $ordendetallereglas_id                =       '1CIX00000001';//es referencial

                        $iddetaller                           =       $funcion->funciones->getCreateIdMaestra('WEB.detalle_documento_asociados');
                        $detaller                             =       new WEBDetalleDocumentoAsociados;
                        $detaller->id                         =       $iddetaller;
                        $detaller->total_producto             =       $CAN_VALOR_VTA;
                        $detaller->total_reglas               =       $total_reglas;
                        $detaller->fecha_crea                 =       $fechaactual;
                        $detaller->usuario_crea               =       Session::get('usuario')->id;
                        $detaller->documento_asociados_id     =       $iddetalle;
                        $detaller->documento_id               =       $documento_relacionado_id;
                        $detaller->producto_id                =       $COD_PRODUCTO;
                        $detaller->regla_id                   =       $regla_id;
                        $detaller->ordendetallereglas_id      =       $ordendetallereglas_id;
                        $detaller->cantidad                   =       $item->cantidad;
                        $detaller->precio                     =       $precio;
                        $detaller->empresa_id                 =       Session::get('empresas')->COD_EMPR;
                        $detaller->centro_id                  =       Session::get('centros')->COD_CENTRO;
                        $detaller->save();

                }


                $TXT_TABLA              =       'CMP.DOCUMENTO_CTBLE';
                $TXT_GLOSA              =       'NOTA DE CREDITO '.$serie.'-'.$nrodoc.' /';

                $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC CMP.REFERENCIA_ASOC_IUD ?,?,?,?,?,?,?,?,?,?,?,?,?,?');
                $stmt->bindParam(1, $accion ,PDO::PARAM_STR);                                   //@IND_TIPO_OPERACION='I',
                $stmt->bindParam(2, $coddocumento[0]  ,PDO::PARAM_STR);                         //@COD_TABLA='IILMNC0000000495',
                $stmt->bindParam(3, $documento_relacionado_id ,PDO::PARAM_STR);                 //@COD_TABLA_ASOC='IILMFC0000005728',
                $stmt->bindParam(4, $TXT_TABLA ,PDO::PARAM_STR);                                //@TXT_TABLA='CMP.DOCUMENTO_CTBLE', 
                $stmt->bindParam(5, $TXT_TABLA ,PDO::PARAM_STR);                                //@TXT_TABLA_ASOC='CMP.DOCUMENTO_CTBLE', 
                $stmt->bindParam(6, $TXT_GLOSA  ,PDO::PARAM_STR);                               //@TXT_GLOSA='NOTA DE CREDITO F005-00000420 / ',
                $stmt->bindParam(7, $vacio  ,PDO::PARAM_STR);                                   //@TXT_TIPO_REFERENCIA='',
                $stmt->bindParam(8, $vacio  ,PDO::PARAM_STR);                                   //@TXT_REFERENCIA='',
                $stmt->bindParam(9, $COD_ESTADO  ,PDO::PARAM_STR);                              //@COD_ESTADO=1,
                $stmt->bindParam(10, $COD_USUARIO_REGISTRO  ,PDO::PARAM_STR);                   //@COD_USUARIO_REGISTRO='PHORNALL',
                $stmt->bindParam(11, $vacio  ,PDO::PARAM_STR);                                  //@TXT_DESCRIPCION='',
                $stmt->bindParam(12, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_AUX1=0,
                $stmt->bindParam(13, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_AUX2=0,
                $stmt->bindParam(14, $valor_cero  ,PDO::PARAM_STR);                             //@CAN_AUX3=0,
                $stmt->execute();



                return $coddocumento[0];


        }       








}


