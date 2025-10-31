<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    use HasFactory;

    /**
     * De attributen die 'mass assignable' zijn.
     * VERPLICHT voor de create() methode in de listener.
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Definieer de relatie met de User (optioneel, maar goede praktijk).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
