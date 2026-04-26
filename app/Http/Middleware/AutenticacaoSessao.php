<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutenticacaoSessao
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('usuario')) {
            return redirect()->route('login')->with('erro', 'Faça login para continuar.');
        }

        return $next($request);
    }
}
