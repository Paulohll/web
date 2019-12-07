<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\WEBRegla,App\WEBIlog,App\WEBMaestro;
use Mail;

class AlertaDesactivarReglas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alertadesactivarreglas:desactivarreglasmanana';
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



        $tipo                           =   'PRD';
        $fecha_actual                   =   date("d-m-Y");
        $fecha_manana                   =   date("Y-m-d",strtotime($fecha_actual."+ 1 days"));


        /****************************************   LIMA    *********************************************************/
        $lista_reglas_desactivaran      =   WEBRegla::join('STD.EMPRESA', 'WEB.reglas.empresa_id', '=', 'STD.EMPRESA.COD_EMPR')
                                            ->join('ALM.CENTRO', 'WEB.reglas.centro_id', '=', 'ALM.CENTRO.COD_CENTRO')
                                            ->where('WEB.reglas.activo','=',1)
                                            ->where('WEB.reglas.tiporegla','<>',$tipo)
                                            ->where('WEB.reglas.estado','=','PU')
                                            ->where('WEB.reglas.centro_id','=','CEN0000000000002')
                                            ->where('WEB.reglas.fechafin','<>','1900-01-01 00:00:00.000')
                                            ->select('WEB.reglas.*','ALM.CENTRO.NOM_CENTRO','STD.EMPRESA.NOM_EMPR')
                                            ->whereRaw('Convert(varchar(10), WEB.reglas.fechafin, 120) = ?', [$fecha_manana])
                                            ->get()
                                            ->toArray();
                                            
        if( count($lista_reglas_desactivaran) > 0){

            // correos from(de)
            $emailfrom = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00001')->first();
            // correos principales y  copias
            $email     = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00004')->first();


            $array = array(
                'lista_reglas' => $lista_reglas_desactivaran,
                'titulo' => 'Reglas se desactivaran el día de mañana',
            );


            Mail::send('emails.alertadesactivarreglas', $array, function($message) use ($emailfrom,$email)
            {

                $emailprincipal     = explode(",", $email->correoprincipal);
                $message->from($emailfrom->correoprincipal, 'REGLAS POR DESACTIVARSE');

                if($email->correocopia<>''){
                    $emailcopias        = explode(",", $email->correocopia);
                    $message->to($emailprincipal)->cc($emailcopias);
                }else{
                    $message->to($emailprincipal);                
                }

                $message->subject($email->descripcion);

            });

        }

        /********************************* GUARDAR LOGS  *********************************/
        $fechatime                           = date("Ymd H:i:s");
        $fecha                               = date("Ymd");
        $cabecera                            = new WEBIlog;
        $cabecera->descripcion               = '(Sistema) Lista de reglas se desactivaran - afecta ('.count($lista_reglas_desactivaran).' reglas)';
        $cabecera->fecha                     = $fecha;
        $cabecera->fechatime                 = $fechatime;
        $cabecera->save();


        /****************************************************   CHICLAYO    *********************************************************/



    }
}
