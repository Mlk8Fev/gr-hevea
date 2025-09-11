<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\NavigationService;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager la navigation avec toutes les vues qui en ont besoin
        View::composer(['partials.sidebar', 'layouts.app'], function ($view) {
            if (auth()->check()) {
                $navigationService = app(NavigationService::class);
                $navigation = $navigationService->getNavigation();
                $view->with('navigation', $navigation);
            } else {
                $view->with('navigation', []);
            }
        });
    }
}
