<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WEBIlog extends Model
{
    protected $table = 'WEB.ilogs';
    public $timestamps=false;

    protected $primaryKey = 'fechatime';
    public $incrementing = false;
    public $keyType = 'string';

}