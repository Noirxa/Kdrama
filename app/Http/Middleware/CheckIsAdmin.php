<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Belangrijk: Importeer de Auth facade
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check of de gebruiker is ingelogd
        // 2. Check of de 'role_id' van de ingelogde gebruiker 1 is
        if (Auth::check() && Auth::user()->role_id == 1) {
            // Zo ja, laat het verzoek doorgaan naar de controller
            return $next($request);
        }

        // Zo nee (het is een gast of een gewone gebruiker),
        // stop het verzoek en geef een 'Verboden' (403) foutmelding.
        abort(403, 'Unauthorized action.');
    }
}
