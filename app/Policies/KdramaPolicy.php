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
        // Admin check (dit was al correct)
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true; // Admin mag altijd, sla de rest van de check over.
        }

        // 5-dagen login check voor normale gebruikers (dit was al correct)
        $startDate = now()->subDays(30);

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
        // 1. Is de gebruiker een admin?
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }

        // 2. Zo nee, is de gebruiker de eigenaar?
        //    (Nu checken we de JUISTE kolom: 'created_by')
        return $user->id === $kdrama->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kdrama $kdrama): bool
    {
        // 1. Is de gebruiker een admin?
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }

        // 2. Zo nee, is de gebruiker de eigenaar?
        return $user->id === $kdrama->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kdrama $kdrama): bool
    {
        // 1. Is de gebruiker een admin?
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }

        // 2. Zo nee, is de gebruiker de eigenaar?
        return $user->id === $kdrama->created_by;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kdrama $kdrama): bool
    {
        // 1. Is de gebruiker een admin?
        if ($user->is_admin == 1 || $user->role_id == 1) {
            return true;
        }

        // 2. Zo nee, is de gebruiker de eigenaar?
        return $user->id === $kdrama->created_by;
    }
}
