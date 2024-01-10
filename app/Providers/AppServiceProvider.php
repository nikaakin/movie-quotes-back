<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ResponseFactory $response): void
    {

        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $signiture = explode('?', $url)[1] ?? '';
            $parts = explode('&', $signiture);
            $expires = explode('=', $parts[0])[1];
            $signiture = 'expires=' . $expires . '000000' . '&signature=' . explode('=', $parts[1])[1];
            $url = env("FRONTEND_URL") . "/api/email/verify/" . $notifiable->getKey() . "/" . sha1($notifiable->getEmailForVerification()) . '/' . app()->getLocale() . '?' . $signiture;
            return (new MailMessage())->view(
                'auth.feedback',
                [
            'greeting' => __('mail.greeting', ["name" => auth()->user()->username]),
            'thank_you' => __('mail.thank_you'),
            'buttonText' => __('mail.verify'),
            'hint' => __('mail.hint'),
            'any_problems' => __('mail.any_problems'),
            'regards' => __('mail.regards'),
            'url' => $url
            ]
            );
        });
    }
}
