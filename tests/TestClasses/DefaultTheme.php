<?php

namespace Aldrumo\ThemeManager\Tests\TestClasses;

use Aldrumo\ThemeManager\Theme\ThemeBase;

class DefaultTheme extends ThemeBase
{
    /** @var string */
    protected $viewsPath = '/../TestViews';

    /** @var string */
    protected $templatesFolder = '/';
}
