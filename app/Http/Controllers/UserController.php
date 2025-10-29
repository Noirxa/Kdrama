<?php

namespace App\Http\Controllers;

use App\Models\User; // Importeer het User model
use Illuminate\Http\Request; // Importeer Request
use Illuminate\Support\Facades\Auth; // Importeer Auth

class UserController extends Controller
{
    /**
     * Toont de lijst met alle gebruikers (Admin-only).
     */
    public function index()
    {
        // Haal alle gebruikers op
        $users = User::all();

        // Stuur de gebruikersdata naar de view
        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Schakelt de 'is_active' status van een gebruiker (Admin-only).
     * Reageert op de Ajax POST request.
     */
    public function toggleStatus(User $user, Request $request)
    {
        // 1. Veiligheidscheck: Voorkom dat de admin zichzelf deactiveert
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Je kunt je eigen account niet deactiveren.'
            ], 403); // 403 = Verboden (Forbidden)
        }

        // 2. De Logica: Keer de status om
        //    (Als het true was, wordt het false. Als het false was, wordt het true)
        $user->is_active = !$user->is_active;
        $user->save();

        // 3. Stuur een succes-response (JSON) terug naar de Ajax-call
        return response()->json([
            'success' => true,
            'is_active' => $user->is_active, // De nieuwe status (true/false)
            'new_status_text' => $user->is_active ? 'Actief' : 'Niet Actief',
            'new_button_text' => $user->is_active ? 'Zet op Niet Actief' : 'Zet op Actief',
        ]);
    }
}
