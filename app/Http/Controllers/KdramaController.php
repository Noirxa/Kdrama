<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kdrama;

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
            // Toon een formulier om een nieuwe kdrama toe te voegen
            return view('kdrama.create');

        }

        /**
         * Store a newly created resource in storage.
         */

    public function store(Request $request)
    {
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kdrama $kdrama)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kdrama $kdrama)
    {

    }
}
