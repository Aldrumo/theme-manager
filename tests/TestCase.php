<?php

namespace Aldrumo\ThemeManager\Tests;

use Aldrumo\ThemeManager\ThemeManagerServerProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ThemeManagerServerProvider::class,
        ];
    }
}
