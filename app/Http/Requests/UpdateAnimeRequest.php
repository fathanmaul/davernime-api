<?php

namespace App\Http\Requests;

use App\Enums\AnimeRating;
use App\Enums\AnimeStatus;
use App\Enums\AnimeType;
use App\Enums\Season;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', Rule::unique('animes')->ignore($this->anime->id), 'string', 'max:255'],
            'english_title' => ['nullable', 'string', 'max:255'],
            'other_title' => ['nullable', 'string', 'max:255'],
            'synopsis' => ['string'],
            'type' => ['nullable', Rule::enum(AnimeType::class)],
            'episodes' => ['nullable', 'integer'],
            'aired_from' => ['nullable', Rule::date()->format('Y-m-d')],
            'aired_to' => ['nullable', Rule::date()->format('Y-m-d')],
            'premiered_season' => ['nullable', Rule::enum(Season::class)],
            'premiered_year' => ['nullable', Rule::date()->format('Y')],
            'duration' => ['nullable', Rule::date()->format('H:i:s')],
            'score' => ['nullable', 'numeric', 'max:10'],
            'status' => ['nullable', Rule::enum(AnimeStatus::class)],
            'rating' => ['nullable', Rule::enum(AnimeRating::class)],
            'trailer_url' => ['nullable', 'max:255'],
            'image_url' => ['nullable', 'max:255'],
            'source' => ['nullable', 'max:255', 'string'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer', 'exists:genres,id'],
            'licensors' => ['nullable', 'array'],
            'licensors.*' => ['integer', 'exists:licensors,id'],
            'studios' => ['nullable', 'array'],
            'studios.*' => ['integer', 'exists:studios,id'],
            'producers' => ['nullable', 'array'],
            'producers.*' => ['integer', 'exists:producers,id'],
        ];
    }
}
