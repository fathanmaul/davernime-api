<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Source;
use Illuminate\Http\Request;

class SourceController extends BaseMasterController
{
    protected $modelClass = Source::class;
}
