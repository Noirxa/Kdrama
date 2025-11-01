<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kdrama;
// 1. GATE FACADE IMPORTEREN
// We hebben deze 'Gate' facade nodig om de policy-check handmatig
// uit te voeren en zelf te kunnen bepalen wat er gebeurt (i.p.v. een 403-fout).
use Illuminate\Support\Facades\Gate;

class KdramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //    test
    public function index(Request $request)
    {
        // Start met een lege query
        $query = Kdrama::query();

        // 🔍 Vrije tekst zoeken
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 🎭 Filter op genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->input('genre'));
        }

        // Haal gefilterde resultaten op
        $kdramas = $query->get();


        // 👉 Toon 5 resultaten per pagina
        $kdramas = $query->paginate(5);

        // Haal alle unieke genres op voor de dropdown
        $genres = Kdrama::select('genre')->distinct()->pluck('genre');

        // Stuur alles naar de view
        return view('kdrama.index', compact('kdramas', 'genres'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 2. AUTORISATIE-CHECK (VERVANGEN)
        // We controleren de 'create' policy. Als de gebruiker NIET mag (denies),
        // voeren we het 'if'-blok uit.
        if (Gate::denies('create', Kdrama::class)) {

            // Stuur de gebruiker terug naar de index-pagina
            return redirect()->route('kdramas.index')
                // En geef een 'error' flits-bericht mee
                ->with('error', 'Je moet op minimaal 5 verschillende dagen ingelogd hebben om een Kdrama te posten.');
        }

        // Als de 'if'-check niet wordt geraakt, mag de gebruiker door
        // en tonen we het formulier.
        return view('kdrama.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 3. AUTORISATIE-CHECK (VERVANGEN)
        // We doen dezelfde controle hier als dubbele beveiliging.
        if (Gate::denies('create', Kdrama::class)) {
            // Stuur terug met dezelfde melding
            return redirect()->route('kdramas.index')
                ->with('error', 'Je moet minimaal 3 keer ingelogd hebben om een Kdrama te posten.');
        }

        // --- Vanaf hier blijft je code hetzelfde ---
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string|max:255',
            'release_year' => 'required|integer',
            'image_url' => 'required|string|max:255',
        ]);

        // Voeg automatisch de ingelogde gebruiker toe
        $validatedData['created_by'] = auth()->id();

        Kdrama::create($validatedData);

        return redirect()->route('kdramas.index')
            ->with('success', 'Kdrama succesvol toegevoegd!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Kdrama $kdrama)
    {
        // Toon een specifieke kdrama
        return view('kdrama.show', compact('kdrama'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kdrama $kdrama)
    {
        // (Hier zou je in de toekomst $this->authorize('update', $kdrama); kunnen toevoegen)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kdrama $kdrama)
    {
        // (Hier zou je in de toekomst $this->authorize('update', $kdrama); kunnen toevoegen)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kdrama $kdrama)
    {
        // (Hier zou je in de toekomst $this->authorize('delete', $kdrama); kunnen toevoegen)
    }
}
