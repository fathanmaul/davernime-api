<?php

namespace App\Http\Requests\Auth;

use App\Traits\BaseResponseTrait;
use App\Traits\HasApiTokensExtended;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    use HasApiTokensExtended, BaseResponseTrait;
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ];
    }

    /**
     * Summary of authenticated
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticated()
    {
        if(!Auth::attempt($this->only('email', 'password'))){
            return $this->resolveErrorResponse(['Invalid credentials'], 401);
        }

        [$access_token, $refresh_token] = $this->generateTokens(Auth::user());

        return $this->resolveSuccessResponse("Login Successfully", [
            "user" => Auth::user(),
            "access_token" => $access_token,
        ])->cookie("refresh_token", $refresh_token, config('sanctum.rt_expiration'), '/', null, false, true);
    }
}
