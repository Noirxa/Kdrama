<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kdrama;
// 1. IMPORTEER BEIDE NODIGE KLASSEN
use Illuminate\Support\Facades\Auth; // Nodig voor auth()->id()
use Illuminate\Support\Facades\Gate; // Nodig voor de Gate::denies() check (de 5-dagen regel)

class KdramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start met een lege query
        $query = Kdrama::query();

        // 🔍 Vrije tekst zoeken (dit blijft hetzelfde)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 🎭 Filter op genre (dit blijft hetzelfde)
        if ($request->filled('genre')) {
            $query->where('genre', $request->input('genre'));
        }

        // Haal gefilterde resultaten op
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
        // --- BEVEILIGING 1: DE "DIEPERE VALIDATIE" REGEL ---
        // We roepen de 'create' methode in KdramaPolicy aan.
        if (Gate::denies('create', Kdrama::class)) {
            // Als de policy 'false' teruggeeft, stuur terug met je
            // specifieke foutmelding over de 5 login dagen.
            return redirect()->route('kdramas.index')
                ->with('error', 'Je moet op minimaal 5 verschillende dagen ingelogd hebben om een Kdrama te posten.');
        }
        // --- EINDE CHECK ---

        // Als de check slaagt, toon het formulier.
        return view('kdrama.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // --- BEVEILIGING 1 (DUBBELE CHECK) ---
        // We doen dezelfde check hier voor het geval iemand
        // het formulier direct probeert te POSTen.
        if (Gate::denies('create', Kdrama::class)) {
            return redirect()->route('kdramas.index')
                ->with('error', 'Je moet op minimaal 5 verschillende dagen ingelogd hebben om een Kdrama te posten.');
        }
        // --- EINDE CHECK ---

        // --- Validatie ---
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string|max:255',
            'release_year' => 'required|integer',
            'image_url' => 'required|string|max:255',
        ]);

        // 4. EIGENAAR (CREATED_BY) OPSLAAN
        // Dit was al correct.
        $validatedData['created_by'] = Auth::id();
        // Maak het Kdrama aan MET de 'created_by'
        Kdrama::create($validatedData);

        return redirect()->route('kdramas.index')
            ->with('success', 'Kdrama succesvol toegevoegd!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Kdrama $kdrama)
    {
        // Hiervoor hebben we 'return true' in de policy,
        // dus geen extra check nodig.
        return view('kdrama.show', compact('kdrama'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kdrama $kdrama)
    {
        // --- BEVEILIGING 2: DE "POLICY" POORTWACHTER CHECK ---
        // Deze ene regel roept de 'update' functie in je KdramaPolicy aan.
        // Het checkt automatisch op Admin OF Eigenaar.
        $this->authorize('update', $kdrama);
        // --- EINDE CHECK ---

        // Als de gebruiker door de check komt, toon de edit-pagina
        return view('kdramas.edit', ['kdrama' => $kdrama]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kdrama $kdrama)
    {
        // --- BEVEILIGING 2: DE "POLICY" POORTWACHTER CHECK ---
        // Precies dezelfde check als in 'edit'.
        $this->authorize('update', $kdrama);
        // --- EINDE CHECK ---

        // --- Validatie ---
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string|max:255',
            'release_year' => 'required|integer',
            'image_url' => 'required|string|max:255',
        ]);

        $kdrama->update($validatedData);

        return redirect()->route('kdramas.index')->with('success', 'Kdrama bijgewerkt!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kdrama $kdrama)
    {
        // --- BEVEILIGING 2: DE "POLICY" POORTWACHTER CHECK ---
        // Deze regel roept de 'delete' functie in je KdramaPolicy aan.
        $this->authorize('delete', $kdrama);
        // --- EINDE CHECK ---

        // Als de check slaagt, verwijder het Kdrama
        $kdrama->delete();

        return redirect()->route('kdramas.index')->with('success', 'Kdrama verwijderd!');
    }
}
