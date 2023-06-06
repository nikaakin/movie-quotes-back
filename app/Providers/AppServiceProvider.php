<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage())->view(
                'auth.feedback',
                [
            'greeting' => __('mail.greeting', ["name"=> auth()->user()->username]),
            'thank_you' => __('mail.thank_you'),
            'buttonText' => __('mail.verify'),
            'hint' => __('mail.another_link'),
            'any_problems' => __('mail.any_problems'),
            'regards' => __('mail.regards'),
            'url'=> $url
            ]
            );
        });
    }
}
