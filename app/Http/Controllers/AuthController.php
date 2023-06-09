<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\EmailVerificationRequest as AuthEmailVerificationRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['password'] = bcrypt($request->password);
        $user = User::where('email', $validatedData['email'])->first();
        if($user) {
            return response()->json(['message' => 'User already exists'], 409);
        }
        $user = User::create($validatedData);
        auth()->login($user);
        event(new Registered($user));
        return  response()->json(['message' => 'User created successfully'], 201);
    }


    public function verification(AuthEmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();
        return redirect(env('FRONTEND_URL').'/news-feed');
    }

    public function resend(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (str_contains($data['username'], '@')) {
            $user  = User::where('email', $data['username'])->first();
            $data['email'] = $data['username'];
            unset($data['username']);
        } else {
            $user  = User::where('username', $data['username'])->first();
        }

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent successfully'], 200);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $remember_me = $data['remember'] ?? false;
        unset($data['remember']);
        if (str_contains($data['username'], '@')) {
            $data['email'] = $data['username'];
            unset($data['username']);
        }

        if (auth()->attempt($data, $remember_me)) {
            if(!auth()->user()->hasVerifiedEmail()) {
                auth()->logout();
                return response()->json(['email_not_verified' => 'Please verify your email'], 401);
            }
            return response()->json(['message' => 'User logged in successfully'], 200);
        }
        return response()->json(['invalid_credentials' => __('auth.failed')], 401);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        $googleUser =  Socialite::driver('google')->stateless()->user();
        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'username' => $googleUser->name,
            'email' => $googleUser->email,
        ]);
        auth()->login($user);

        return response()->json([
            'message' => 'user logged in',
            'data' => auth()->user()
        ]);
    }
}
