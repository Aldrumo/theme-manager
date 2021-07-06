<?php

namespace Aldrumo\ThemeManager;

use Aldrumo\ThemeManager\Http\Livewire\ThemeAdmin;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ThemeManagerServerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerConfigs();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootLivewire();
        $this->bootMigrations();
        $this->bootPublishes();
        $this->bootViews();
    }

    protected function bootLivewire()
    {
        Livewire::component('theme-admin', ThemeAdmin::class);
    }

    protected function bootMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function bootPublishes()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/theme-manager.php' => config_path('theme-manager.php'),
            ],
            'aldrumo'
        );

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/' . 'ThemeManager'),
        ], 'aldrumo-ThemeManager-views');
    }

    protected function bootViews()
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'ThemeManager'
        );
    }

    protected function registerBindings()
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

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/theme-manager.php',
            'theme-manager'
        );
    }
}
