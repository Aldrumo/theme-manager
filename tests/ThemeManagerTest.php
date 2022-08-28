<?php

namespace Aldrumo\ThemeManager\Tests;

use Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotInstalledException;
use Aldrumo\ThemeManager\Tests\TestClasses\AnotherTheme;
use Aldrumo\ThemeManager\Tests\TestClasses\AnotherThemeServiceProvider;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultTheme;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemeManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function bootThemes()
    {
        app()->register(DefaultThemeServiceProvider::class);
        app()->register(AnotherThemeServiceProvider::class);
    }

    /** @test */
    public function active_theme_is_set()
    {
        $this->bootThemes();

        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->assertInstanceOf(
            DefaultTheme::class,
            app(ThemeManager::class)->activeTheme()
        );
    }

    /** @test */
    public function active_theme_is_throws_exception_when_not_set()
    {
        $this->bootThemes();

        $this->expectException(ActiveThemeNotSetException::class);

        app(ThemeManager::class)->activeTheme();
    }

    /** @test */
    public function active_theme_is_throws_exception_when_invalid_theme_set()
    {
        $this->bootThemes();

        $this->expectException(ThemeNotFoundException::class);

        app(ThemeManager::class)->activeTheme('ThemeDoesNotExist');
    }

    /** @test */
    public function can_get_list_of_available_themes()
    {
        $this->bootThemes();

        $themes = app(ThemeManager::class)->availableThemes();

        $this->assertEquals(
            [
                'DefaultTheme',
                'AnotherTheme',
            ],
            $themes->toArray()
        );
    }

    /** @test */
    public function can_get_empty_list_when_no_theme_set()
    {
        $themes = app(ThemeManager::class)->availableThemes();

        $this->assertEmpty($themes->toArray());
    }

    /** @test */
    public function can_activate_theme()
    {
        $this->bootThemes();
        app(ThemeManager::class)->installTheme('AnotherTheme');
        app(ThemeManager::class)->activateTheme('AnotherTheme');

        $this->assertInstanceOf(
            AnotherTheme::class,
            app(ThemeManager::class)->activeTheme()
        );
    }

    /** @test */
    public function can_switch_theme()
    {
        $this->bootThemes();
        app(ThemeManager::class)->installTheme('DefaultTheme');
        app(ThemeManager::class)->installTheme('AnotherTheme');

        app(ThemeManager::class)->activateTheme('DefaultTheme');

        app(ThemeManager::class)->activateTheme('AnotherTheme', 'DefaultTheme');

        $this->assertInstanceOf(
            AnotherTheme::class,
            app(ThemeManager::class)->activeTheme()
        );
    }

    /** @test */
    public function activate_theme_throws_exception_when_invalid_old_theme_set()
    {
        $this->bootThemes();
        app(ThemeManager::class)->installTheme('DefaultTheme');
        app(ThemeManager::class)->activateTheme('DefaultTheme');
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->expectException(ThemeNotInstalledException::class);
        app(ThemeManager::class)->activateTheme('AnotherTheme', 'ThemeDoesNotExist');
    }

    /** @test */
    public function activate_theme_throws_exception_when_invalid_new_theme_set()
    {
        $this->bootThemes();
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->expectException(ThemeNotFoundException::class);
        app(ThemeManager::class)->activateTheme('ThemeDoesNotExist', 'DefaultTheme');
    }

    /** @test */
    public function can_install_theme()
    {
        $this->bootThemes();
        $theme = app(ThemeManager::class)->installTheme('AnotherTheme');

        $this->assertInstanceOf(
            AnotherTheme::class,
            $theme
        );
    }

    /** @test */
    public function can_uninstall_theme()
    {
        $this->bootThemes();
        $themeManager = app(ThemeManager::class);
        $themeManager->installTheme('AnotherTheme');

        $theme = $themeManager->uninstallTheme('AnotherTheme');

        $this->assertInstanceOf(
            AnotherTheme::class,
            $theme
        );
    }
}
