<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        return $request->registered();
    }
}
