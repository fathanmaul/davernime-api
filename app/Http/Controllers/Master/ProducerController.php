<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Producer;
use Illuminate\Http\Request;

class ProducerController extends BaseMasterController
{
    protected $modelClass = Producer::class;
}
