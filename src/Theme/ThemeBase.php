<?php

namespace Aldrumo\ThemeManager\Theme;

abstract class ThemeBase
{
    /** @var ThemeServiceProvider */
    protected $serviceProvider;

    /**
     * ThemeBase constructor.
     * @param ThemeServiceProvider $serviceProvider
     */
    public function __construct(ThemeServiceProvider $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * Anything that would go in your service providers "register" method should go here
     */
    public function register() : void
    {
        //
    }

    /**
     * Anything that would go in your service providers "boot" method should go here
     */
    public function boot() : void
    {
        $this->serviceProvider->setViews(__DIR__ . '/../resources/views');
    }

    /**
     * @return array
     */
    abstract public function availableViews(): array;
}
