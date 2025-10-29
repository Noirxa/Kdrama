<?php

namespace App\Http\Controllers;

use App\Models\Kdrama;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * 📄 Toon een lijst van alle reviews (optioneel, niet altijd nodig).
     */
    public function index()
    {
        // Haal alle reviews op met de bijbehorende gebruiker en Kdrama
        $reviews = Review::with(['user', 'kdrama'])->get();

        // Stuur dit door naar een overzichtspagina (optioneel)
        return view('kdrama.index', compact('reviews'));
    }

    /**
     * 📝 Laat het formulier zien om een nieuwe review te maken.
     */
    public function create(Kdrama $kdrama)
    {
        // Geeft de juiste Kdrama mee aan het formulier
        return view('reviews.create', compact('kdrama'));
    }

    /**
     * 💾 Sla een nieuwe review op in de database.
     */
    public function store(Request $request, Kdrama $kdrama)
    {
        // 1️⃣ Valideer de data
        $validatedData = $request->validate([
            'rating' => 'required|numeric|min:1|max:10',
            'comment' => 'required|string|max:255',
        ]);


        // 2️⃣ Maak de review aan via de Kdrama-relatie
        $kdrama->reviews()->create([
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
            'user_id' => auth()->id(), // ID van ingelogde gebruiker
        ]);

        // 3️⃣ Stuur gebruiker terug naar de Kdrama detailpagina
        return redirect()
            ->route('kdramas.show', $kdrama)
            ->with('success', 'Review succesvol toegevoegd!');
    }

    /**
     * 🔍 Toon een specifieke review (meestal niet apart nodig in dit project).
     */
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    /**
     * ✏️ Laat het formulier zien om een review te bewerken.
     */
    public function edit(Review $review)
    {
        // Controleer of de ingelogde gebruiker deze review mag aanpassen
        if ($review->user_id !== auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'Je mag alleen je eigen review bewerken.');
        }

        return view('Kdrama.edit', compact('review'));
    }

    /**
     * 🔄 Werk een bestaande review bij.
     */
    public function update(Request $request, Review $review)
    {
        // 1️⃣ Controleer of gebruiker eigenaar is van deze review
        if ($review->user_id !== auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'Je mag alleen je eigen review aanpassen.');
        }

        // 2️⃣ Valideer de data
        $validated = $request->validate([
            'rating' => 'required|string',
            'comment' => 'required|string|max:255',
        ]);

        // 3️⃣ Update de review
        $review->update($validated);

        // 4️⃣ Stuur gebruiker terug naar de juiste Kdrama
        return redirect()
            ->route('kdramas.show', $review->kdrama)
            ->with('success', 'Je review is bijgewerkt!');
    }

    /**
     * ❌ Verwijder een review.
     */
    public function destroy(Review $review)
    {
        // Controleer of gebruiker eigenaar is
        if ($review->user_id !== auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'Je mag alleen je eigen review verwijderen.');
        }

        // Bewaar de Kdrama voordat de review wordt verwijderd
        $kdrama = $review->kdrama;

        // Verwijder de review
        $review->delete();

        // Stuur terug naar de detailpagina van de Kdrama
        return redirect()
            ->route('kdramas.show', $kdrama)
            ->with('success', 'Je review is verwijderd.');
    }
}
