<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\WEBRegla,App\WEBIlog,App\WEBMaestro,App\WEBPedido,App\User,App\STDEmpresaDireccion;
use Mail;

class PedidoNotificacionAutorizar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedidonotificacion:autorizar';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $fecha_actual                   =   date("Y-m-d");
        $fecha_manana                   =   date("Y-m-d",strtotime($fecha_actual."+ 1 days"));


        $lista_pedidos                  =   WEBPedido::where('WEB.pedidos.ind_notificacion_autorizacion','=',0)
                                            //->where('WEB.pedidos.fecha_venta','=',$fecha_actual)
                                            ->get();


        foreach($lista_pedidos as $item){

                // correos from(de)
            $emailfrom          =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00001')->first();
            // correos principales y  copias
            $email              =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00006')->first();

            $direccion          =   STDEmpresaDireccion::where('COD_DIRECCION','=',$item->direccionentrega->COD_DIRECCION)->first();

            $vendedor           =   User::where('id','=',$item->usuario_crea)->first();
            $correorv           =   $vendedor->email;

            $array              =   Array(
                'NP'            =>  $item,
                'vendedor'      =>  $vendedor,
                'detalle'       =>  $item->detallepedido,
                'direccion'     =>  $direccion
            );

            $codigo             =   $item->codigo;


            Mail::send('emails.notificacionautorizacion', $array, function($message) use ($emailfrom,$email,$correorv,$codigo)
            {

                $emailprincipal     = explode(",", $email->correoprincipal.','.$correorv);
        
                $message->from($emailfrom->correoprincipal, 'El pedido '.' '.$codigo.' fue autorizado.');

                if($email->correocopia<>''){
                    $emailcopias        = explode(",", ltrim(rtrim($email->correocopia)));
                    $message->to($emailprincipal)->cc($emailcopias);
                }else{
                    $message->to($emailprincipal);                
                }
                $message->subject($email->descripcion);

            });

            $pedido                                     =   WEBPedido::where('WEB.pedidos.id','=',$item->id)->first();
            $pedido->ind_notificacion_autorizacion      =   1;
            $pedido->save();
            

        }
                     
    }
}
