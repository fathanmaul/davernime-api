<?php

namespace App\Http\Controllers;

use App\BaseResponseTrait;
use App\Http\Requests\StoreAnimeRequest;
use App\Http\Requests\UpdateAnimeRequest;
use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnimeController extends Controller
{
    use BaseResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $anime = Anime::paginate(10, ['anime_id', 'title']);
        $anime = Anime::paginate(10);
        return $this->resolveSuccessResponse("Data fetched successfully", $anime);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnimeRequest $request)
    {
        $validated = $request->validated();

        $validated['anime_id'] = Anime::orderByDesc('anime_id')->value('anime_id') + 1 ?? 1;

        if (!empty($validated['image_url']) && Storage::disk('public')->exists($validated['image_url'])) {
            $filename = basename($validated['image_url']);
            $newPath = "thumbnails/$filename";
            Storage::disk('public')->move($validated['image_url'], $newPath);
            $validated['image_url'] = $newPath;
        }

        $anime = Anime::create($validated);

        $anime->genres()->attach($request->validated('genres'));
        $anime->licensors()->attach($request->validated('licensors'));
        $anime->producers()->attach($request->validated('producers'));
        $anime->studios()->attach($request->validated('studios'));

        return $this->resolveSuccessResponse(data: ['anime_id' => $anime->anime_id, 'title' => $anime->title]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anime $anime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @TODO implement this method for updating anime
     */
    public function update(UpdateAnimeRequest $request, Anime $anime)
    {
        $validated = $request->validated();

        $anime->update($validated);
        $anime->genres()->sync($request->validated('genres'));
        $anime->licensors()->sync($request->validated('licensors'));
        $anime->producers()->sync($request->validated('producers'));
        $anime->studios()->sync($request->validated('studios'));

        return $this->resolveSuccessResponse("$anime->title has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $anime_id)
    {
        $anime = Anime::where('anime_id', $anime_id)->first(['anime_id', 'title', 'image_url']);
        if (!$anime) {
            return $this->resolveErrorResponse(["Data not found."], 404);
        }
        if (Storage::disk('public')->exists($anime->image_url)) {
            Storage::disk('public')->delete($anime->image_url);
        }
        $anime->delete();
        return $this->resolveSuccessResponse("$anime->title has been deleted!");
    }

    /**
     * Store temporary thumbnail for preview
     */
    public function storeTempThumbnail(Request $request)
    {
        $request->validate([
            'thumbnail' => 'required|image|max:1024',
        ]);

        $file = $request->file('thumbnail');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('temp', $filename, 'public');

        return response()->json([
            'path' => $path, // simpan ke database saat form submit
            'url' => \Storage::url($path), // bisa digunakan untuk preview
        ]);
    }
}
