<?php

namespace Aldrumo\ThemeManager\Exceptions;

use Illuminate\Http\Request;

class ActiveThemeNotSetException extends \LogicException
{
    public function render(Request $request)
    {
        $view = config('theme-manager.exceptions.activeTheme');
        if ($view !== null) {
            return response()->view($view);
        }
    }
}
