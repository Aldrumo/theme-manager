<?php

namespace Aldrumo\ThemeManager\Theme;

use Aldrumo\Support\Traits\CanGetPackageName;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    use CanGetPackageName;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $themeName = $this->packageName('ServiceProvider');
        $theme = $this->packageFQDN('ServiceProvider');

        $this->app->singleton(
            'theme:' . $themeName,
            function () use ($theme) {
                return new $theme($this);
            }
        );
    }

    /**
     * @param string $path
     */
    public function setViews(string $path)
    {
        $themeName = $this->packageName('ServiceProvider');

        $this->loadViewsFrom($path, $themeName);

        $this->publishes([
            $path => resource_path('views/vendor/' . $themeName),
        ], $themeName . '-views');
    }
}
