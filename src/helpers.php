<?php

use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Contracts\Container\BindingResolutionException;

if (! function_exists('themeView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function themeView($view = null, $data = [], $mergeData = [])
    {
        try {
            $themeManager = resolve(ThemeManager::class);
            $theme = $themeManager->activeTheme()->packageName() . '::';
        } catch (BindingResolutionException $e) {
            throw new ThemeNotFoundException;
        }

        return view($theme . $view, $data, $mergeData);
    }
}
