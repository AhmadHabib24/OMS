<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();

                $view->with('globalUnreadNotificationCount', $user->unreadAppNotifications()->count());

                $view->with('globalRecentNotifications', $user->appNotifications()
                    ->take(5)
                    ->get());
            } else {
                $view->with('globalUnreadNotificationCount', 0);
                $view->with('globalRecentNotifications', collect());
            }
        });
    }
}
