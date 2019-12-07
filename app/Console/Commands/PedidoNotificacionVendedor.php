<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\WEBRegla,App\WEBIlog,App\WEBMaestro,App\WEBPedido,App\User;
use Mail;

class PedidoNotificacionVendedor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedidonotificacion:vendedor';
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


        $lista_pedidos                  =   WEBPedido::where('WEB.pedidos.ind_notificacion','=',0)
                                            ->where('WEB.pedidos.fecha_venta','=',$fecha_actual)
                                            ->get();


        //print_r(count($lista_pedidos ));


        foreach($lista_pedidos as $item){

            // correos from(de)
            $emailfrom          =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00001')->first();
            // correos principales y  copias
            $email              =   WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00005')->first();

                
            $saldocli           =   DB::select('exec RPS.SALDO_TRAMO_CUENTA ?,?,?,?,?,?,?,?,?,?,?', array('','','','',date("Y-m-d"),$item->cliente_id,'TCO0000000000068','','','',''));
            $vendedor           =   User::where('id','=',$item->usuario_crea)->first();


            $array      =  Array(
                'NP'        =>  $item,
                'saldo'     =>  $saldocli,
                'vendedor'  =>  $vendedor,
                'detalle'   =>  $item->detallepedido
            );

            $correorv           =   $vendedor->email;
            $codigo             =   $item->codigo;


            Mail::send('emails.notificacionpedido', $array, function($message) use ($emailfrom,$email,$codigo,$correorv)
            {

                $emailprincipal     = explode(",", $email->correoprincipal.','.$correorv);
        
                $message->from($emailfrom->correoprincipal, 'Nuevo pedido registrado'.' '.$codigo);

                if($email->correocopia<>''){
                    $emailcopias        = explode(",", $email->correocopia);
                    $message->to($emailprincipal)->cc($emailcopias);
                }else{
                    $message->to($emailprincipal);                
                }
                $message->subject($email->descripcion);

            });



            $pedido                         =   WEBPedido::where('WEB.pedidos.id','=',$item->id)->first();
            $pedido->ind_notificacion       =   1;
            $pedido->save();

        }
                     
    }
}
