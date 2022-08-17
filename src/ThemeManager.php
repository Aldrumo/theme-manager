<?php

namespace Aldrumo\ThemeManager;

use Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotInstalledException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotUninstalledException;
use Aldrumo\ThemeManager\Models\Theme;
use Aldrumo\ThemeManager\Theme\ThemeBase;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

class ThemeManager
{
    /** @var Repository */
    protected $cache;

    /** @var ThemeBase */
    protected $activeTheme;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function activeTheme(?string $theme = null) : ?ThemeBase
    {
        if ($theme === null && $this->activeTheme === null) {
            throw new ActiveThemeNotSetException;
        }

        if ($theme === null) {
            return $this->activeTheme;
        }

        try {
            $this->activeTheme = resolve('theme:' . $theme);
        } catch (BindingResolutionException $e) {
            throw new ThemeNotFoundException;
        }

        app()->singleton(
            static::class,
            function ($app) {
                return $this;
            }
        );

        $this->activeTheme->register();
        $this->activeTheme->boot();

        return $this->activeTheme;
    }

    public function activateTheme(string $newTheme, ?string $oldTheme = null) : ?ThemeBase
    {
        if ($oldTheme !== null) {
            try {
                $oldTheme = resolve('theme:' . $oldTheme);
            } catch (BindingResolutionException $e) {
                throw new ThemeNotFoundException;
            }
            $oldTheme->deactivate();
        }

        try {
            $theme = resolve('theme:' . $newTheme);
        } catch (BindingResolutionException $e) {
            throw new ThemeNotFoundException;
        }

        $theme->activate();

        $this->activeTheme($newTheme);

        return $theme;
    }

    public function installTheme(string $theme) : ?ThemeBase
    {
        try {
            $themeBase = resolve('theme:' . $theme);
        } catch (BindingResolutionException $e) {
            throw new ThemeNotFoundException;
        }

        if ($themeBase->install()) {
            return $themeBase;
        }

        throw new ThemeNotInstalledException();
    }

    public function uninstallTheme(string $theme) : ?ThemeBase
    {
        try {
            $themeBase = resolve('theme:' . $theme);
        } catch (BindingResolutionException $e) {
            throw new ThemeNotFoundException;
        }

        if ($themeBase->uninstall()) {
            return $themeBase;
        }

        throw new ThemeNotUninstalledException();
    }

    public function availableThemes() : Collection
    {
        return $this->discoverThemes()
            ->map(
                function ($item) {
                    return $item->packageName();
                }
            );
    }

    public function discoverThemes() : Collection
    {
         return collect(app()->tagged('aldrumo-theme'));
    }

    public function getInstalledThemes() : Collection
    {
         return Theme::orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get()
             ->mapWithKeys(
                 function ($item) {
                     return [$item->name => $item];
                 }
             );
    }
}
