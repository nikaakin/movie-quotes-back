<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = request()->query('locale');
        if($locale && in_array($locale, config('app.available_locales'))) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en');
        }
        if ($locale) {
            return redirect()->to(url()->current().'?'.http_build_query($request->except("locale")));
        }
        return $next($request);
    }
}
