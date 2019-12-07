<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Authaw
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!Session::has('empresas')){
            if(!Session::has('usuario')){
                return Redirect()->to('/login');
            }else{
                return Redirect()->to('/acceso');
            }
        }else{
            if(Session::has('usuario')){
                return $next($request);
            }else{
                return Redirect()->to('/login');
            }

        }

        
    }
}
