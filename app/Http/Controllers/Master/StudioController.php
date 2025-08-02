<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Studio;
use Illuminate\Http\Request;

class StudioController extends BaseMasterController
{
    protected string $modelClass = Studio::class;
}
