<?php

namespace App\Http\Middleware;

use App\Support\Currency;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        config(['currency.current' => Currency::current()]);

        return $next($request);
    }
}
