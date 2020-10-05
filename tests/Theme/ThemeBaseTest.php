<?php

namespace Aldrumo\ThemeManager\Tests\Theme;

use Aldrumo\ThemeManager\Tests\TestCase;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\ThemeManager\ThemeManager;

class ThemeBaseTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);

        // in app, theme service providers should be autoloaded via composer
        $providers[] = DefaultThemeServiceProvider::class;

        return $providers;
    }

    /** @test */
    public function can_load_available_views()
    {
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $views = app(ThemeManager::class)->activeTheme()
            ->availableViews();

        $this->assertEquals(
            [
                "DefaultTheme::Content.left-col" => "Content.left-col",
                "DefaultTheme::Content.right-col" => "Content.right-col",
                "DefaultTheme::Foo.Bar.contact" => "Foo.Bar.contact",
                "DefaultTheme::home" => "home"
            ],
            $views->toArray()
        );
    }
}
