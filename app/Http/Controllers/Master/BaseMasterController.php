<?php

namespace App\Http\Controllers\Master;

use App\BaseResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMasterRequest;
use App\Http\Requests\UpdateMasterRequest;

class BaseMasterController extends Controller
{
    use BaseResponseTrait;
    /**
     * The model class associated with the controller.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    public $modelClass;

    public function index()
    {
        $items = $this->modelClass::paginate(10, ['id', 'name', 'slug']);
        return $this->resolveSuccessResponse(data: $items);
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
    public function store(StoreMasterRequest $request)
    {
        $item = $this->modelClass::create([
            'name' => $request->input('name'),
            'slug' => \Illuminate\Support\Str::slug($request->input('name'))
        ]);

        return $this->resolveSuccessResponse(class_basename($this->modelClass) . " created successfully" ,$item);
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
    public function update(UpdateMasterRequest $request, string $id)
    {
        $model = $this->modelClass::find($id);
        if (!$model) {
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

        $updatedItem = $this->modelClass::find($id);

        return $this->resolveSuccessResponse(class_basename($this->modelClass) . " updated successfully.", $updatedItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = $this->modelClass::find($id);
        if ($item === null)
            return $this->resolveErrorResponse([class_basename($this->modelClass) . ' data not found.']);
        $this->modelClass::where('id', $id)->delete();
        return $this->resolveSuccessResponse(class_basename($this->modelClass) . ' deleted successfully.');
    }
}
