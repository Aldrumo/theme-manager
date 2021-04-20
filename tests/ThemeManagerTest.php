<?php

namespace Aldrumo\ThemeManager\Tests;

use Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
use Aldrumo\ThemeManager\Tests\TestClasses\AnotherTheme;
use Aldrumo\ThemeManager\Tests\TestClasses\AnotherThemeServiceProvider;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultTheme;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\ThemeManager\ThemeManager;

class ThemeManagerTest extends TestCase
{
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
    public function can_switch_theme()
    {
        $this->bootThemes();
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        app(ThemeManager::class)->installTheme('AnotherTheme', 'DefaultTheme');

        $this->assertInstanceOf(
            AnotherTheme::class,
            app(ThemeManager::class)->activeTheme()
        );
    }

    /** @test */
    public function can_install_theme()
    {
        $this->bootThemes();
        app(ThemeManager::class)->installTheme('AnotherTheme');

        $this->assertInstanceOf(
            AnotherTheme::class,
            app(ThemeManager::class)->activeTheme()
        );
    }

    /** @test */
    public function install_theme_throws_exception_when_invalid_old_theme_set()
    {
        $this->bootThemes();
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->expectException(ThemeNotFoundException::class);
        app(ThemeManager::class)->installTheme('AnotherTheme', 'ThemeDoesNotExist');
    }

    /** @test */
    public function install_theme_throws_exception_when_invalid_new_theme_set()
    {
        $this->bootThemes();
        app(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->expectException(ThemeNotFoundException::class);
        app(ThemeManager::class)->installTheme('ThemeDoesNotExist', 'DefaultTheme');
    }
}
