<?php

namespace Aldrumo\ThemeManager;

use Illuminate\Support\ServiceProvider;

class ThemeManagerServerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            ThemeManager::class,
            function ($app) {
                return new ThemeManager(
                    $app['cache.store']
                );
            }
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/theme-manager.php',
            'theme-manager'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/theme-manager.php' => config_path('theme-manager.php'),
            ],
            'aldrumo'
        );
    }
}
