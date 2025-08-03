<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterRequest;
use App\MasterResponseTrait;
use Exception;
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
        return $this->resolveErrorResponse(
            ['You won\'t be able to open create page in here']
        );
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MasterRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
