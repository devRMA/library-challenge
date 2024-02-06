<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class AcceptLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request                                                                                           $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response) $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $user_locales = (new Agent())->languages();
        $locale = head($user_locales);
        $locale = Str::replace('-', '_', $locale);
        if (is_array($locale)) {
            $locale = head($locale);
        }
        $locale = Str::snake(Str::lower($locale));
        App::setLocale($locale);

        return $next($request);
    }
}
