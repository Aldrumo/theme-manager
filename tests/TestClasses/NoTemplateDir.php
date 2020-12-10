<?php

namespace Aldrumo\ThemeManager\Tests\TestClasses;

use Aldrumo\ThemeManager\Theme\ThemeBase;

class NoTemplateDir extends ThemeBase
{
    /** @var string */
    protected $viewsPath = '/../foobar';
}
