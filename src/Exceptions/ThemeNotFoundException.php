<?php

namespace Aldrumo\ThemeManager\Exceptions;

use Illuminate\Http\Request;

class ThemeNotFoundException extends \LogicException
{
    public function render(Request $request)
    {
        $view = config('theme-manager.exceptions.themeNotFound');
        if ($view !== null) {
            return response()->view($view);
        }
    }
}
