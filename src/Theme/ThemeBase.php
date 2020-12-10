<?php

namespace Aldrumo\ThemeManager\Theme;

use Aldrumo\Support\Traits\CanGetPackageName;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class ThemeBase
{
    use CanGetPackageName;

    /** @var ThemeServiceProvider */
    protected $serviceProvider;

    /** @var string */
    protected $viewsPath = '/../resources/views/templates';

    /** @var Collection */
    protected $availableViews;

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
        $this->serviceProvider->setViews($this->getPath() . $this->viewsPath);
    }

    public function availableViews() : Collection
    {
        if ($this->availableViews === null) {
            $this->availableViews = collect([]);
            $this->getViews($this->getPath() . $this->viewsPath);
        }

        return $this->availableViews;
    }

    public function getPath() : string
    {
        return dirname(
            (new \ReflectionClass($this))->getFileName()
        );
    }

    protected function getViews($path)
    {
        $theme = $this->packageName();

        collect(File::allFiles($path))
            ->each(
                function ($item) use ($theme) {
                    $view = (string) Str::of($item->getRelativePathname())
                        ->replace('.blade.php', '')
                        ->replace('/', '.');

                    $key = $theme . '::' . $view;

                    $this->availableViews->put($key, $view);
                }
            );
    }
}
