<?php

namespace Aldrumo\ThemeManager\Tests;

use Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
use Aldrumo\ThemeManager\Tests\TestClasses\AnotherThemeServiceProvider;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultTheme;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\ThemeManager\ThemeManager;

class ThemeManagerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);

        // in app, theme service providers should be autoloaded via composer
        $providers[] = DefaultThemeServiceProvider::class;
        $providers[] = AnotherThemeServiceProvider::class;

        return $providers;
    }

    /** @test */
    public function active_theme_is_set()
    {
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->assertInstanceOf(
            DefaultTheme::class,
            app(ThemeManager::class)->activeTheme()
        );
    }

    /** @test */
    public function active_theme_is_throws_exception_when_not_set()
    {
        $this->expectException(ActiveThemeNotSetException::class);

        app(ThemeManager::class)->activeTheme();
    }

    /** @test */
    public function active_theme_is_throws_exception_when_invalid_theme_set()
    {
        $this->expectException(ThemeNotFoundException::class);

        app(ThemeManager::class)->activeTheme('ThemeDoesNotExist');
    }

    /** @test */
    public function can_get_list_of_available_themes()
    {
        $themes = app(ThemeManager::class)->availableThemes();

        $this->assertEquals(
            [
                'DefaultTheme',
                'AnotherTheme',
            ],
            $themes->toArray()
        );
    }
}
