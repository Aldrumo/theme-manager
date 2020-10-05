<?php

namespace Aldrumo\ThemeManager;

use Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
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

    public function availableThemes() : Collection
    {
        return collect(app()->tagged('aldrumo-theme'))
            ->map(
                function ($item) {
                    return $item->packageName();
                }
            );
    }
}
