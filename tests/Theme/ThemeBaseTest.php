<?php

namespace Aldrumo\ThemeManager\Tests\Theme;

use Aldrumo\ThemeManager\Tests\TestCase;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\ThemeManager\Tests\TestClasses\NoTemplateDirServiceProvider;
use Aldrumo\ThemeManager\ThemeManager;

class ThemeBaseTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);

        // in app, theme service providers should be autoloaded via composer
        $providers[] = DefaultThemeServiceProvider::class;
        $providers[] = NoTemplateDirServiceProvider::class;

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
                "Content.left-col" => "Content.left-col",
                "Content.right-col" => "Content.right-col",
                "Foo.Bar.contact" => "Foo.Bar.contact",
                "home" => "home"
            ],
            $views->toArray()
        );
    }

    /** @test */
    public function handles_no_template_dir()
    {
        app(ThemeManager::class)->activeTheme('NoTemplateDir');

        $views = app(ThemeManager::class)->activeTheme()
            ->availableViews();

        $this->assertEquals(
            [],
            $views->toArray()
        );
    }
}
