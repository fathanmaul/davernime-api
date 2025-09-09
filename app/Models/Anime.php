<?php

namespace App\Models;

use App\Enums\AnimeStatus;
use App\Models\Master\Genre;
use App\Models\Master\Licensor;
use App\Models\Master\Producer;
use App\Models\Master\Studio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Anime extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'anime_id'];

    protected $fillable = [
        'anime_id',
        'title',
        'english_title',
        'other_title',
        'synopsis',
        'type',
        'episodes',
        'source',
        'aired_from',
        'aired_to',
        'premiered_season',
        'premiered_year',
        'duration',
        'rating',
        'status',
        'score',
        'trailer_url',
        'image_url',
    ];

    protected $casts = [
        'status' => AnimeStatus::class
    ];

    protected $primaryKey = 'anime_id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'anime_genres', 'anime_id', 'genre_id');
    }

    public function licensors(): BelongsToMany
    {
        return $this->belongsToMany(Licensor::class, 'anime_licensors', 'anime_id', 'licensor_id');
    }

    public function producers(): BelongsToMany
    {
        return $this->belongsToMany(Producer::class, 'anime_producers', 'anime_id', 'producer_id');
    }

    public function studios(): BelongsToMany
    {
        return $this->belongsToMany(Studio::class, 'anime_studios', 'anime_id', 'studio_id');
    }
}
