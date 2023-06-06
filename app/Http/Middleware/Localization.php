<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = request()->header('Accept-Language');
        if($locale && in_array($locale, config('app.available_locales'))) {
            app()->setLocale(request()->header('Accept-Language'));
        } else {
            app()->setLocale('en');
        }
        return $next($request);
    }
}
