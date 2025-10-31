<?php

namespace App\Listeners;

// Importeer de specifieke Event-class
use Illuminate\Auth\Events\Login;
// Importeer het model dat je wilt gebruiken
use App\Models\LoginHistory;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    // Zorg dat 'Login' hier staat, niet 'object'
    public function handle(Login $event): void
    {
        // Sla een record op voor de ingelogde gebruiker
        LoginHistory::create([
            'user_id' => $event->user->id,
        ]);
    }
}
