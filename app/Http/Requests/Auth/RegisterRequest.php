<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Traits\BaseResponseTrait;
use App\Traits\HasApiTokensExtended;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends FormRequest
{
    use BaseResponseTrait, HasApiTokensExtended;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Summary of registered
     * @return \Illuminate\Http\JsonResponse
     */
    public function registered()
    {
        $user = User::create($this->validated());
        [$access_token, $refresh_token] = $this->generateTokens($user);

        return $this->resolveSuccessResponse("User Registered Successfully", [
            "user" => $user,
            "access_token" => $access_token,
        ])->cookie("refresh_token", $refresh_token, config('sanctum.rt_expiration'), '/', null, false, true);
    }
}
