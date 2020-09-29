<?php

namespace Aldrumo\ThemeManager\Tests\Theme;

use Aldrumo\ThemeManager\Tests\TestCase;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultTheme;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;

class ThemeServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);

        // in app, theme service providers should be autoloaded via composer
        $providers[] = DefaultThemeServiceProvider::class;

        return $providers;
    }

    /** @test */
    public function can_provider_register_theme_file()
    {
        $this->assertInstanceOf(
            DefaultTheme::class,
            $this->app['theme:DefaultTheme']
        );

        $this->assertInstanceOf(
            DefaultTheme::class,
            app('theme:DefaultTheme')
        );
    }
}
