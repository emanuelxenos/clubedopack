<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Contracts\PaymentGatewayInterface::class, function ($app) {
            $gateway = config('services.payments.gateway', 'mock');
            if ($gateway === 'asaas') {
                return new \App\Services\Payments\AsaasGateway();
            }
            return new \App\Services\Payments\MockGateway();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
