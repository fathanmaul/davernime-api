<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterResource;
use App\MasterResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exceptions;

class BaseMasterController extends Controller
{
    use MasterResponseTrait;
    protected string $modelClass;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
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
