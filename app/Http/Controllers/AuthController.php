<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\EmailVerificationRequest as AuthEmailVerificationRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['password'] = bcrypt($request->password);
        $user = User::create($validatedData);
        auth()->login($user);
        event(new Registered($user));
        return  response()->json(['message' => 'User created successfully'], 201);
    }


    public function verification(AuthEmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();
        auth()->logout();
        return redirect(env('FRONTEND_URL').'/news-feed');
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $remember_me = $data['remember_me'] ?? false;
        unset($data['remember']);
        if (str_contains($data['username'], '@')) {
            $data['email'] = $data['username'];
            unset($data['username']);
        }

        if (auth()->attempt($data, $remember_me)) {
            return redirect()->route('home');
        }
        return response()->json(['message' => 'User logged in successfully'], 200);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }


}
