<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function login(LoginRequest $request): Response
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('token');

        return response(['access_token' => $token->plainTextToken]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return Application|Response
     */
    public function logout(): \Illuminate\Foundation\Application|Response
    {
        request()->user()->currentAccessToken()->delete();

        return response(['message' => 'Successfully logged out']);
    }
}
