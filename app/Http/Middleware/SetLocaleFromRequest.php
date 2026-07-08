<?php

namespace App\Http\Middleware;

use App\Models\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $activeCodes = Cache::remember('locales:active_codes', 3600, function () {
            return Locale::where('is_active', true)->pluck('code')->all();
        });

        $defaultCode = Cache::remember('locales:default_code', 3600, function () {
            return Locale::where('is_default', true)->value('code') ?? config('app.locale');
        });

        $requested = (string) $request->query('locale', '');

        $locale = in_array($requested, $activeCodes, true) ? $requested : $defaultCode;

        App::setLocale($locale);
        $request->attributes->set('locale', $locale);

        return $next($request);
    }
}
