<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        // También se puede con $request->user()->rol 

        if( auth()->user()->rol === 1 ){
            // En caso de que no sea rol 2, redireccionar al usuario hacia home
            return redirect()->route('home');
        }
        return $next($request);
    }
}
