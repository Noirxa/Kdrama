<?php

namespace App\Policies;

use App\Models\Kdrama;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;

class KdramaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kdrama $kdrama): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // <-- HIER IS DE AANPASSING
        // We controleren op BEIDE mogelijke admin-kolommen:
        // 1. Heet de kolom 'is_admin' en is die 1?
        // 2. Of heet de kolom 'role_id' en is die 1? (gebaseerd op je data)
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true; // Admin mag altijd, sla de rest van de check over.
        }
        // <-- EINDE AANPASSING

        // <-- DIT IS DE NORMALE CHECK VOOR REGULIERE GEBRUIKERS
        // Bepaal de startdatum (vandaag - 30 dagen)
        $startDate = now()->subDays(30);

        // Haal het AANTAL UNIEKE DAGEN op waarop is ingelogd
        $distinctLoginDays = LoginHistory::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->get();

        $loginCount = $distinctLoginDays->count();
        $requiredLogins = 5;

        return $loginCount >= $requiredLogins;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kdrama $kdrama): bool
    {
        // <-- OOK HIER AANGEPAST
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }

        // Normale check (bijv. is de gebruiker de eigenaar?)
        // return $user->id === $kdrama->created_by;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kdrama $kdrama): bool
    {
        // <-- OOK HIER AANGEPAST
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }

        // return $user->id === $kdrama->created_by;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kdrama $kdrama): bool
    {
        // <-- OOK HIER AANGEPAST
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kdrama $kdrama): bool
    {
        // <-- OOK HIER AANGEPAST
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }
        return false;
    }
}

