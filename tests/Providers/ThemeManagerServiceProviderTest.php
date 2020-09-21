<?php

namespace Aldrumo\ThemeManager\Tests\Providers;

use Aldrumo\ThemeManager\Tests\TestCase;
use Aldrumo\ThemeManager\ThemeManager;

class ThemeManagerServiceProviderTest extends TestCase
{
    /** @test */
    public function theme_manager_is_registered()
    {
        $this->assertInstanceOf(
            ThemeManager::class,
            $this->app[ThemeManager::class]
        );

        $this->assertInstanceOf(
            ThemeManager::class,
            app(ThemeManager::class)
        );
    }
}
