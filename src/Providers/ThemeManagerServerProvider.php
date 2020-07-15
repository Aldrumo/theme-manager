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
    }
}
