<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importeer Auth
use Symfony\Component\HttpFoundation\Response;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check: Is de gebruiker ingelogd? EN is zijn 'is_active' status 'false' (0)?
        if (Auth::check() && ! Auth::user()->is_active) {

            // 1. Log de gebruiker onmiddellijk uit
            Auth::logout();

            // 2. Maak de sessie ongeldig
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 3. Stuur hem terug naar de login-pagina met jouw melding
            return redirect('/login')->with('error', 'Dit account is gedeactiveerd of verbannen.');
        }

        // Als alles in orde is (of als het een gast is), ga gewoon door.
        return $next($request);
    }
}
