<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && ! is_null(Auth::user()->lang)) {
            session()->put('lang', Auth::user()->lang);
        }

        app()->setLocale(session('lang', 'ml'));

        if (! request()->routeIs('admin.*')) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->hasRole(['topic staff', 'hadith staff', 'quran staff'])) {
            $lang = Config::get('app.languages')[app()->getLocale()];
            Config::set('app.languages', [app()->getLocale() => $lang]);
        }

        return $next($request);
    }
}
