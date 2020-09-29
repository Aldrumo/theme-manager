<?php

namespace Aldrumo\ThemeManager\Tests\TestClasses;

use Aldrumo\ThemeManager\Theme\ThemeBase;

class DefaultTheme extends ThemeBase
{
    public function availableViews() : array
    {
        return [];
    }
}
