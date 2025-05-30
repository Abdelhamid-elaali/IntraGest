<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class BrevoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/brevo.php', 'brevo'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Brevo as a mail transport
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory())->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.key')
                )
            );
        });

        // Set up mail configuration for Brevo
        if (config('mail.default') === 'brevo') {
            Config::set('mail.mailers.brevo', [
                'transport' => 'brevo',
            ]);
        }
    }
}
