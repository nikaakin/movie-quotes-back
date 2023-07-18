<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\EmailVerificationRequest as AuthEmailVerificationRequest;
use App\Http\Requests\auth\ForgotRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\ResetRequest;
use App\Http\Requests\auth\UpdateRequest;
use App\Mail\ResetMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
        auth()->login($user);
        event(new Registered($user));
        return  response()->json(['message' => 'User created successfully'], 201);
    }


    public function update(UpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $email = $data['email'];

        if(isset($data['verifiedEmail'])) {
            $user = User::firstWhere('email', $email)->update(['email'=> $data['verifiedEmail']]);
            return  response()->json(['message' => 'User updated successfully', "user"=> $user], 201);
        }

        if($data['google_id']) {
            unset($data['password']);
            unset($data['newEmail']);
        } else {
            isset($data['password']) && $data['password'] = bcrypt($request->password);

            if(isset($data['newEmail'])) {
                $user = User::firstWhere('email', $email);
                $newEmail = $data['newEmail'];
                unset($data['newEmail']);
                $locale = app()->getLocale();
                Mail::to($email)->send(new ResetMail(
                    __('mail.greeting', ["name"=> $user->username]),
                    __('mail.reset_email_update_hint'),
                    __('mail.reset_email_update_button'),
                    __('mail.hint'),
                    __('mail.any_problems'),
                    __('mail.regards'),
                    env('FRONTEND_URL') ."/$locale/news-feed/profile/?email=$email&newEmail=$newEmail",
                ));
            }
        }

        if($request->file('image')) {
            $url = $request->file('image')->store('avatars', 'public');
            $data['image'] = env('APP_URL') .'/storage/'. $url;
        } else {
            unset($data['image']);
        }
        $user = User::updateOrCreate(['email'=> $email], $data);
        return  response()->json(['message' => 'User updated successfully', "user"=> $user], 201);
    }


    public function verification(AuthEmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();
        return response()->json(['message' => 'Email verified successfully'], 200);
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
                auth()->user()->sendEmailVerificationNotification();
                return response()->json(['email_not_verified' => 'Please verify your email'], 401);
            }
            return response()->json(['message' => 'User logged in successfully', "user"=> auth()->user(), ], 200);
        }
        return response()->json(['password' => __('auth.failed')], 401);
    }

    public function logout(): JsonResponse
    {
        auth()->guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    public function forgot(ForgotRequest $request): JsonResponse
    {
        $data = $request->validated();
        $email  = $data['email'];
        $user = User::firstWhere('email', $email);
        if($user->google_id) {
            return response()->json(['details'=>['email' => __('validation.google_email')]], 401);
        }
        $token = Str::random(64);


        DB::table('password_reset_tokens')->updateOrInsert(
            ['email'=> $data['email']],
            [
                'token'      => $token,
                'created_at' => now(),
            ]
        );
        $locale = app()->getLocale();
        Mail::to($user)
        ->send(
            new ResetMail(
                __('mail.greeting', ["name"=> $user->username]),
                __('mail.reset_password_hint'),
                __('mail.reset_password_button'),
                __('mail.hint'),
                __('mail.any_problems'),
                __('mail.regards'),
                env('FRONTEND_URL') ."/$locale/?token=$token&email=$email",
            )
        );

        return response()->json(['message' => 'User logged out successfully'], 200);
    }


    public function reset(ResetRequest $request): JsonResponse
    {
        $data = $request->validated();
        $email = $data['email'];
        $password_reset_token = DB::table('password_reset_tokens')->where(['email'=> $email])->first();

        if(!$password_reset_token) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
        if($password_reset_token->token !== $data['token']) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        User::where(['email' => $email])->update([
            'password'=> bcrypt($data['password']),
        ]);

        DB::table('password_reset_tokens')->where(['email' => $email])->delete();

        return response()->json(['message' => 'Password changed succefully'], 200);

    }

    public function isAuthenticated(): JsonResponse
    {
        if(!auth()->user()->email_verified_at && !auth()->user()->google_id) {
            return response()->json(['email_not_verified' => 'Please verify your email'], 401);
        }

        $user = auth()->user();
        return response()->json(['is_authenticated' => true, 'user'=> $user], 200);
    }


    public function googleRedirect(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback(): JsonResponse
    {
        $googleUser =  Socialite::driver('google')->stateless()->user();
        $google_id = $googleUser->getId();

        $user =User::where(['username'=> $googleUser->name ?? $googleUser->getNickname()])->first();

        if($user && $user->google_id !== $google_id) {
            return response()->json(['details'=>['username' => __('validation.exists', ['attribute'=> __('field_names.username')])]], 401);
        }
        $user =User::where(['email'=> $googleUser->getEmail()])->first();

        if($user && $user->google_id !== $google_id) {
            return response()->json(['details'=>['username' => __('validation.exists', ['attribute'=> __('field_names.email')])]], 401);
        }
        if(!$user) {
            $user = User::create(
                [
                    'google_id' => $google_id,
                    'email' => $googleUser->getEmail(),
                    'username' => $googleUser->name ?? $googleUser->getNickname(),
                    'password' => null,
                    'image' => $googleUser->getAvatar(),
                ]
            );
        }

        auth()->login($user);
        return response()->json([
            'message' => 'user logged in',
            'user' => auth()->user(),
        ]);
    }
}
