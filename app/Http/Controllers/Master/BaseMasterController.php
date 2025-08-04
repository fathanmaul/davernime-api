<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterRequest;
use App\MasterResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exceptions;

class BaseMasterController extends Controller
{
    use MasterResponseTrait;
    /**
     * The model class associated with the controller.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $modelClass;

    public function index()
    {
        $items = $this->modelClass::get();
        return $this->resolveSuccessResponse($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->resolveErrorResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MasterRequest $request)
    {
        $this->modelClass::create([
            'name' => $request->input('name'),
            'slug' => \Illuminate\Support\Str::slug($request->input('name'))
        ]);

        return $this->resolveSuccessResponse(message: class_basename($this->modelClass) . " created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = $this->modelClass::findOrFail($id);
        return $this->resolveSuccessResponse($item);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->resolveErrorResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MasterRequest $request, string $id)
    {
        $model = $this->modelClass::find($id);

        if ($model === null) {
            return $this->resolveErrorResponse(["Data not found."]);
        }

        $newName = $request->input("name");
        $newSlug = \Illuminate\Support\Str::slug($newName);

        if ($model->name === $newName && $model->slug === $newSlug) {
            return $this->resolveSuccessResponse("No changes detected.");
        }

        $model->update([
            "name" => $newName,
            "slug" => $newSlug,
        ]);

        return $this->resolveSuccessResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = $this->modelClass::find($id);
        if ($item === null) return $this->resolveErrorResponse([class_basename($this->modelClass) . ' data not found.']);
        $this->modelClass::where('id', $id)->delete();
        $this->resolveSuccessResponse(message: class_basename($this->modelClass) . '');
    }
}
