<?php

namespace Aldrumo\ThemeManager\Tests\Theme;

use Aldrumo\ThemeManager\Exceptions\CannotUninstallActiveThemeException;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyActiveException;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyInstalledException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotActiveException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotInstalledException;
use Aldrumo\ThemeManager\Tests\TestCase;
use Aldrumo\ThemeManager\Tests\TestClasses\AnotherThemeServiceProvider;
use Aldrumo\ThemeManager\Tests\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\ThemeManager\Tests\TestClasses\NoTemplateDirServiceProvider;
use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemeBaseTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);

        // in app, theme service providers should be autoloaded via composer
        $providers[] = DefaultThemeServiceProvider::class;
        $providers[] = AnotherThemeServiceProvider::class;
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
                "Content.left-col",
                "Content.right-col",
                "Foo.Bar.contact",
                "home",
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

    /** @test */
    public function can_install_theme_into_database()
    {
        $theme = resolve('theme:DefaultTheme');

        $this->assertTrue(
            $theme->install()
        );

        $this->assertDatabaseHas(
            'themes',
            [
                'name' => 'DefaultTheme',
                'is_active' => false,
            ]
        );
    }

    /** @test */
    public function exception_if_theme_already_installed()
    {
        $this->expectException(ThemeAlreadyInstalledException::class);

        $theme = resolve('theme:DefaultTheme');
        $theme->install();
        $theme->install();
    }

    /** @test */
    public function can_activate_theme_in_database()
    {
        $theme = resolve('theme:DefaultTheme');
        $theme->install();

        $this->assertTrue(
            $theme->activate()
        );

        $this->assertDatabaseHas(
            'themes',
            [
                'name' => 'DefaultTheme',
                'is_active' => true,
            ]
        );
    }

    /** @test */
    public function exception_if_theme_not_installed()
    {
        $this->expectException(ThemeNotInstalledException::class);

        $theme = resolve('theme:DefaultTheme');
        $theme->activate();
    }

    /** @test */
    public function exception_if_theme_already_activated()
    {
        $this->expectException(ThemeAlreadyActiveException::class);

        $theme = resolve('theme:DefaultTheme');
        $theme->install();
        $theme->activate();

        $theme->activate();
    }

    /** @test */
    public function can_deactivate_theme_in_database()
    {
        $theme = resolve('theme:DefaultTheme');
        $theme->install();
        $theme->activate();

        $this->assertDatabaseHas(
            'themes',
            [
                'name' => 'DefaultTheme',
                'is_active' => true,
            ]
        );

        $this->assertTrue(
            $theme->deactivate()
        );

        $this->assertDatabaseHas(
            'themes',
            [
                'name' => 'DefaultTheme',
                'is_active' => false,
            ]
        );
    }

    /** @test */
    public function exception_if_theme_not_active()
    {
        $this->expectException(ThemeNotActiveException::class);

        $theme = resolve('theme:DefaultTheme');
        $theme->install();
        $theme->deactivate();
    }

    /** @test */
    public function can_uninstall_theme_from_database()
    {
        $theme = resolve('theme:DefaultTheme');
        $theme->install();

        $this->assertDatabaseHas(
            'themes',
            [
                'name' => 'DefaultTheme',
                'is_active' => false,
            ]
        );

        $this->assertTrue(
            $theme->uninstall()
        );

        $this->assertDatabaseMissing(
            'themes',
            [
                'name' => 'DefaultTheme',
            ]
        );
    }

    /** @test */
    public function exception_if_theme_not_installed_when_uninstalling()
    {
        $this->expectException(ThemeNotInstalledException::class);

        $theme = resolve('theme:DefaultTheme');
        $theme->uninstall();
    }

    /** @test */
    public function exception_if_theme_is_active_when_uninstalling()
    {
        $this->expectException(CannotUninstallActiveThemeException::class);

        $theme = resolve('theme:DefaultTheme');
        $theme->install();
        $theme->activate();

        $theme->uninstall();
    }

    /** @test */
    public function can_return_if_theme_is_installed_or_not()
    {
        $theme = resolve('theme:DefaultTheme');
        $theme->install();

        $this->assertTrue(
            $theme->isInstalled()
        );

        $theme1 = resolve('theme:AnotherTheme');

        $this->assertFalse(
            $theme1->isInstalled()
        );
    }

    /** @test */
    public function can_return_if_theme_is_active_or_not()
    {
        $theme = resolve('theme:DefaultTheme');
        $theme->install();
        $theme->activate();

        $this->assertTrue(
            $theme->isActive()
        );

        $theme1 = resolve('theme:AnotherTheme');
        $theme1->install();

        $this->assertFalse(
            $theme1->isActive()
        );
    }
}
