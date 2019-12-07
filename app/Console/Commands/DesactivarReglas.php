<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\WEBIlog,App\WEBMaestro;
use Mail;

class DesactivarReglas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desactivarreglas:desactivarreglasContrato';
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


        /********************************* DESACTIVAR REGLAS ****************************/
        $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON;EXEC web.culminar_reglas_contrato');
        $stmt->execute();
        $resultado = $stmt->fetch();


        /********************************* GUARDAR LOGS  *********************************/
        $fechatime                           = date("Ymd H:i:s");
        $fecha                               = date("Ymd");
        $cabecera                            = new WEBIlog;
        $cabecera->descripcion               = '(Sistema) desactivar reglas - afecta ('.$resultado['cantidad'].' reglas)';
        $cabecera->fecha                     = $fecha;
        $cabecera->fechatime                 = $fechatime;
        $cabecera->save();

        /********************************* ENVIAR CORREO SI SE DESACTIVA ALGUNA REGLA  *********************************/

        if( (int)$resultado['cantidad'] > 0){

            // correos from(de)
            $emailfrom = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00001')->first();
            // correos principales y  copias
            $email     = WEBMaestro::where('codigoatributo','=','0001')->where('codigoestado','=','00002')->first();

            $array = array(
                'cantidad' => $resultado['cantidad']
            );

            Mail::send('emails.desactivarreglas', $array, function($message) use ($emailfrom,$email)
            {

                $emailprincipal     = explode(",", $email->correoprincipal);
                
                $message->from($emailfrom->correoprincipal, 'DESACTIVAR REGLAS');

                if($email->correocopia<>''){
                    $emailcopias        = explode(",", $email->correocopia);
                    $message->to($emailprincipal)->cc($emailcopias);
                }else{
                    $message->to($emailprincipal);                
                }
                $message->subject($email->descripcion);

            });
        }

    }
}
