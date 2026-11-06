<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @var array<int, string>
     */
    public const SUPPORTED = ['bn', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->hasSession()
            ? $request->session()->get('locale', config('app.locale'))
            : config('app.locale');

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
