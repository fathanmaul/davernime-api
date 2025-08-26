<?php

namespace App\Traits;

use App\Enums\TokenAbility;
use App\Models\User;
use Illuminate\Support\Str;

trait HasApiTokensExtended
{
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

        return [$access_token->plainTextToken, $refresh_token->plainTextToken];
    }
}
