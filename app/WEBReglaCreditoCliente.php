<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBReglaCreditoCliente extends Model
{
    protected $table = 'WEB.reglacreditoclientes';
    public $timestamps=false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['activo','cliente_id','canlimitecredito','condicionpago_id','clasificacion','fecha_crea','usuario_crea'];


    public function cliente()
    {
        return $this->belongsTo('App\STDEmpresa', 'cliente_id', 'COD_EMPR');
    }
    public function tipopago()
    {
        return $this->belongsTo('App\CMPCategoria', 'condicionpago_id', 'COD_CATEGORIA');
    }
}
