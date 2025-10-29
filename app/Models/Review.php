<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Dit bestand is de blauwdruk voor één 'Review'.
class Review extends Model
{
    // Handig voor het maken van test-data.
    use HasFactory;

    // --- DE REGELS ---
    // Dit is de lijst met velden die we vertrouwen vanuit een formulier.
    // Om veiligheidsredenen mag Laravel alleen deze velden invullen.
    protected $fillable = [
        'rating',
        'comment',
        'user_id',
    ];


    // --- DE KOPPELINGEN ---
    // Hier definiëren we de relaties met andere modellen.

    // Deze functie zegt: "Elke review hoort bij één K-drama".
    public function kdrama(): BelongsTo
    {
        // Laravel koppelt dit automatisch via de 'kdrama_id' kolom in de database.
        return $this->belongsTo(Kdrama::class);
    }

    // Deze functie zegt: "Elke review is geschreven door één User".
    public function user(): BelongsTo
    {
        // Laravel koppelt dit automatisch via de 'user_id' kolom in de database.
        return $this->belongsTo(User::class);
    }
}
