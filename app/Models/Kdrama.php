<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Toegevoegd, goede praktijk
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- BELANGRIJK: Toegevoegd voor de nieuwe relatie

class Kdrama extends Model
{
    use HasFactory; // Toegevoegd, goede praktijk

    /**
     * De velden die massaal mogen worden toegewezen (mass assignable).
     *
     * 'user_id' is toegevoegd om de eigenaar (de 'User') correct op te slaan.
     * 'created_by' is verwijderd, omdat 'user_id' de juiste, relationele manier is om dit te doen.
     */
    protected $fillable = [
        'title',
        'description',
        'genre',
        'release_year',
        'image_url',
        'created_by',
    ];

    /**
     * De reviews die bij deze K-drama horen.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * De gebruiker (eigenaar) die dit K-drama heeft geüpload.
     * Een Kdrama 'behoort tot' (belongsTo) één User.
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
