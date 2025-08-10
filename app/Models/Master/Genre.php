<?php

namespace App\Models\Master;

use App\Models\Anime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "genres";

    protected $guarded = ['id'];

     public function animes(): BelongsToMany
    {
        return $this->belongsToMany(Anime::class, 'anime_genres');
    }
}
