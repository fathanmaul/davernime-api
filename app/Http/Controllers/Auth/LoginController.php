<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        return $request->authenticated();
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $user->tokens()->where('pair_id', $user->currentAccessToken()->pair_id)->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
