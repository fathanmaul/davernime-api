<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Genre;
use Illuminate\Http\Request;

class GenreController extends BaseMasterController
{
    protected string $modelClass = Genre::class;
}
