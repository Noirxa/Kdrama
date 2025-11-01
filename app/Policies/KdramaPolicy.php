<?php

namespace App\Policies;

use App\Models\Kdrama;
use App\Models\User;
use App\Models\LoginHistory;
// 1. IMPORT VAN DB FACADE
// Deze is nodig voor de 'DATE()' functie in de query
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;

class KdramaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Iedereen mag de index-pagina zien
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kdrama $kdrama): bool
    {
        return true; // Iedereen mag een individuele Kdrama zien
    }

    /**
     * Determine whether the user can create models.
     * * 2. AANGEPASTE CREATE METHODE
     * Dit is de bijgewerkte methode die voldoet aan de "verschillende dagen" eis.
     */
    public function create(User $user): bool
    {
        // Bepaal de startdatum (vandaag - 30 dagen)
        // Logins van langer geleden tellen niet meer mee (dit is het "aftellen")
        $startDate = now()->subDays(30);

        // Haal het AANTAL UNIEKE DAGEN op waarop is ingelogd
        $distinctLoginDays = LoginHistory::where('user_id', $user->id)
            // Filter 1: Alleen logins van de afgelopen 30 dagen
            ->where('created_at', '>=', $startDate)

            // Selecteer alleen de datum (zonder tijd)
            ->selectRaw('DATE(created_at) as date')

            // Zorg dat we elke datum maar één keer krijgen
            ->distinct()

            // Haal de resultaten op
            ->get();

        // Tel het aantal unieke dagen dat we hebben gevonden
        $loginCount = $distinctLoginDays->count();

        // De eis is nu 5 (zoals in je schoolvoorbeeld)
        $requiredLogins = 5;

        // Geef true terug als het aantal gelijk of hoger is, anders false
        return $loginCount >= $requiredLogins;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kdrama $kdrama): bool
    {
        return false; // Pas dit aan naar je eigen logica (bijv. is eigenaar)
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kdrama $kdrama): bool
    {
        return false; // Pas dit aan naar je eigen logica
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kdrama $kdrama): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kdrama $kdrama): bool
    {
        return false;
    }
}

