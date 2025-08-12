<?php

namespace App\Http\Controllers;

use App\Enums\TokenAbility;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\BaseResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use BaseResponseTrait;

    public function doRegister(RegisterRequest $request)
    {
        $input = $request->validated();
        $user = User::create($input);
        [$access_token, $refresh_token] = $this->generateTokens($user);

        return $this->resolveSuccessResponse("User registered successfully", ['user' => $user, 'token' => $access_token->plainTextToken, 'refresh_token' => $refresh_token->plainTextToken]);
    }

    public function doLogin(LoginRequest $request)
    {
        $input = $request->validated();
        $user = User::where('email', $input['email'])->first();

        if (!$user || !Hash::check($input['password'], $user->password)) {
            return $this->resolveErrorResponse(["Invalid credentials"], 401);
        }

        [$access_token, $refresh_token] = $this->generateTokens($user);

        return $this->resolveSuccessResponse("Login successfully", ['user' => ['name' => $user->name, 'email' => $user->email, 'avatar_url' => $user->avatar_url], 'token' => $access_token->plainTextToken, 'refresh_token' => $refresh_token->plainTextToken]);
    }

    public function doLogout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->resolveSuccessResponse("Logout successfully");
    }

    public function getUser()
    {
        $user = auth()->user();
        return $this->resolveSuccessResponse("User retrieved successfully", ['user' => $user]);
    }

    public function refreshToken(Request $request)
    {
        $currentRefreshToken = $request->bearerToken();
        $refreshToken = PersonalAccessToken::findToken($currentRefreshToken);

        if (!$refreshToken || !$refreshToken->can(TokenAbility::ISSUE_ACCESS_TOKEN->value) || $refreshToken->expires_at->isPast()) {
            return $this->resolveErrorResponse(["Invalid refresh token"], 401);
        }

        $user = $refreshToken->tokenable;
        $pair_id = $refreshToken->pair_id;
        PersonalAccessToken::where('pair_id', $pair_id)->delete();

        [$access_token, $refresh_token] = $this->generateTokens($user);

        return $this->resolveSuccessResponse("Token Refreshed Successfully", ['access_token' => $access_token->plainTextToken, 'refresh_token' => $refresh_token->plainTextToken]);
    }

    private function generateTokens(User $user): array
    {
        $pair_id = Str::uuid()->toString();

        $access_token = $user->createToken(
            name: 'access_token',
            abilities: [TokenAbility::ACCESS_API],
            expiresAt: now()->addMinutes(config('sanctum.expiration'))
        );
        $access_token->accessToken->update(['pair_id' => $pair_id]);

        $refresh_token = $user->createToken(
            name: 'refresh_token',
            abilities: [TokenAbility::ISSUE_ACCESS_TOKEN],
            expiresAt: now()->addMinutes(config('sanctum.rt_expiration'))
        );
        $refresh_token->accessToken->update(['pair_id' => $pair_id]);

        return [$access_token, $refresh_token];
    }
}
