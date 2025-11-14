<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class EmailTemplateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new MailMessage)
                ->subject('Verifica tu dirección de email - ProServi')
                ->view('emails.verify', [
                    'verificationUrl' => $verificationUrl,
                    'user' => $notifiable
                ]);
        });
    }
}