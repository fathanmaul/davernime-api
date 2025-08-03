<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Licensor;
use Illuminate\Http\Request;

class LicensorController extends BaseMasterController
{
    protected $modelClass = Licensor::class;
}
