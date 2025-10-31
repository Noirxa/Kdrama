<?php

namespace App\Policies;

use App\Models\Kdrama;
use App\Models\User;
use App\Models\LoginHistory; // BELANGRIJK: Importeren
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
     * DIT IS DE BELANGRIJKE METHODE
     */
    public function create(User $user): bool
    {
        // DIT IS DE VEREISTE ELOQUENT QUERY
        $loginCount = LoginHistory::where('user_id', $user->id)->count();

        // Controleer of het aantal groter of gelijk is aan 3
        return $loginCount >= 3;
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
