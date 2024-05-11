<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AccountService;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Event;


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
        Event::listen(
            \Illuminate\Notifications\Events\NotificationFailed::class,
            \App\Listeners\DeleteExpiredNotificationTokens::class
        );
    }
}
