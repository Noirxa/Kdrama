<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kdrama extends Model
{
    protected $fillable = ['title', 'description', 'genre', 'release_year', 'image_url', 'created_by'];

    /**
     * De reviews die bij deze K-drama horen.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    //
}

