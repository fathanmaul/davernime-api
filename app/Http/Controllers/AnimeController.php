<?php

namespace App\Http\Controllers;

use App\Traits\BaseResponseTrait;
use App\Traits\HandleImage;
use App\Http\Requests\StoreAnimeRequest;
use App\Http\Requests\UpdateAnimeRequest;
use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnimeController extends Controller implements HasMiddleware
{
    use BaseResponseTrait, HandleImage;
    private array $relations = ['genres', 'licensors', 'producers', 'studios'];

    public static function middleware()
    {
        return [
            new Middleware(['auth:sanctum', 'ensureIsAdmin'], except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $anime = Anime::paginate(10);
        return $this->resolveSuccessResponse("Data fetched successfully", $anime);
    }

    /**
     * Can't be used in the current API design.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\StoreAnimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAnimeRequest $request)
    {
        $validated = $request->validated();

        $validated['anime_id'] = Anime::orderByDesc('anime_id')->value('anime_id') + 1 ?? 1;

        $image_path = $request->hasFile('image_file')
            ? $this->moveImageFile($request->file('image_file'))
            : ($validated['image_url'] ?? null);

        $validated['image_url'] = $image_path;

        $anime = Anime::create($validated);

        $anime->genres()->attach($request->validated('genres'));
        $anime->licensors()->attach($request->validated('licensors'));
        $anime->producers()->attach($request->validated('producers'));
        $anime->studios()->attach($request->validated('studios'));

        return $this->resolveSuccessResponse(data: ['anime_id' => $anime->anime_id, 'title' => $anime->title]);
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Anime $anime or anime_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $anime_id)
    {
        $anime = Anime::with($this->relations)
            ->where('anime_id', $anime_id)
            ->first();
        if (!$anime) {
            return $this->resolveErrorResponse(['Data not found.'], 404);
        }
        foreach ($this->relations as $relation) {
            $anime->$relation->makeHidden(['created_at', 'updated_at']);
        }
        return $this->resolveSuccessResponse("Data fetched successfully", $anime);
    }

    /**
     * This method is not used in the current API design.
     */
    public function edit(Anime $anime)
    {
        return $this->resolveErrorResponse(status: 400);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateAnimeRequest  $request
     * @param  \App\Models\Anime  $anime
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAnimeRequest $request, Anime $anime)
    {
        $validated = $request->validated();
        if ($request->hasFile('image_file')) {
            $this->deleteImageIfExists($anime->image_url);
            $validated['image_url'] = $this->moveImageFile($request->file('image_file'));
        } elseif (!empty($validated['image_url']) && $validated['image_url'] !== $anime->image_url) {
            if (!filter_var($validated['image_url'], FILTER_VALIDATE_URL)) {
                $this->deleteImageIfExists($anime->image_url);
            }
        } else {
            $validated['image_url'] = $anime->image_url;
        }

        $anime->update($validated);
        foreach ($this->relations as $relation){
            if($request->has($relation)){
                $anime->$relation()->sync($request->validated($relation));
            }
        }

        return $this->resolveSuccessResponse("$anime->title has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     * @param string $anime_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $anime_id)
    {
        $anime = Anime::where('anime_id', $anime_id)->first(['anime_id', 'title', 'image_url']);
        if (!$anime) {
            return $this->resolveErrorResponse(["Data not found."], 404);
        }

        if ($anime->image_url && !filter_var($anime->image_url, FILTER_VALIDATE_URL)) {
            if (Storage::disk('public')->exists($this->getStoragePath($anime->image_url))) {
                Storage::disk('public')->delete($this->getStoragePath($anime->image_url));
            }
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
            'path' => $path,
            'url' => \Storage::url($path),
        ]);
    }
}
