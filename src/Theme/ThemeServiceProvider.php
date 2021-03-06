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
        $this->app->tag('theme:' . $themeName, ['aldrumo-theme']);
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

    /**
     * @param array $paths
     * @param null $groups
     */
    public function setPublishes(array $paths, $groups = null)
    {
        $this->publishes($paths, $groups);
    }
}
