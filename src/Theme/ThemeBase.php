<?php

namespace Aldrumo\ThemeManager\Theme;

use Aldrumo\Support\Traits\CanGetPackageName;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyInstalledException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotInstalledException;
use Aldrumo\ThemeManager\Models\Theme;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

abstract class ThemeBase
{
    use CanGetPackageName;

    /** @var ThemeServiceProvider */
    protected $serviceProvider;

    /** @var string */
    protected $viewsPath = '/../resources/views';

    /** @var string */
    protected $templatesFolder = '/templates';

    /** @var Collection */
    protected $availableViews;

    /** @var bool */
    protected $installed = null;

    /** @var bool */
    protected $active = null;

    protected ?Theme $themeModel = null;

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
    public function register(): void
    {
        //
    }

    /**
     * Anything that would go in your service providers "boot" method should go here
     */
    public function boot(): void
    {
        $this->serviceProvider->setViews($this->getPath() . $this->viewsPath);
        $this->serviceProvider->setViews($this->getPath() . $this->viewsPath . $this->templatesFolder);
    }

    public function installCallback(): void
    {
        //
    }

    public function uninstallCallback(): void
    {
        //
    }

    public function activateCallback(): void
    {
        //
    }

    public function deactivateCallback(): void
    {
        //
    }

    public function install(): bool
    {
        $result = Theme::install($this);

        if ($result) {
            $this->installCallback();
            $this->installed = true;
            return true;
        }

        return false;
    }

    public function uninstall(): bool
    {
        $model = $this->getModel();
        $result = $model->uninstall();

        if ($result) {
            $this->uninstallCallback();
            $this->installed = false;
            return true;
        }

        return false;
    }

    public function activate(): bool
    {
        $model = $this->getModel();
        $result = $model->activate();

        if ($result) {
            $this->activateCallback();
            $this->active = true;
            return true;
        }

        return false;
    }

    public function deactivate(): bool
    {
        $model = $this->getModel();
        $result = $model->deactivate();

        if ($result) {
            $this->deactivateCallback();
            $this->active = false;
            return true;
        }

        return false;
    }

    public function availableViews(): Collection
    {
        if ($this->availableViews === null) {
            $this->availableViews = collect([]);
            $this->getViews(
                $this->getPath() . $this->viewsPath . $this->templatesFolder
            );
        }

        return $this->availableViews;
    }

    public function getPath(): string
    {
        return dirname(
            (new \ReflectionClass($this))->getFileName()
        );
    }

    public function isInstalled(): bool
    {
        if ($this->installed !== null) {
            return $this->installed;
        }

        try {
            $this->getModel();
        } catch (ThemeNotInstalledException $e) {
            return $this->installed = false;
        }

        return $this->installed = true;
    }

    public function isActive(): bool
    {
        if ($this->active !== null) {
            return $this->active;
        }

        try {
            $model = $this->getModel();
        } catch (ThemeNotInstalledException $e) {
            return $this->active = false;
        }

        return $this->active = $model->is_active;
    }

    protected function getModel(): Theme
    {
        if ($this->themeModel !== null) {
            return $this->themeModel;
        }

        $model = Theme::where('name', $this->packageName('ServiceProvider'))->first();

        if ($model === null) {
            throw new ThemeNotInstalledException();
        }

        return $this->themeModel = $model;
    }

    protected function getViews($path)
    {
        try {
            $files = File::allFiles($path);
        } catch (DirectoryNotFoundException $e) {
            return;
        }

        collect($files)
            ->each(
                function ($item) {
                    $view = (string) Str::of($item->getRelativePathname())
                        ->replace('.blade.php', '')
                        ->replace('/', '.');

                    $this->availableViews->push($view);
                }
            );
    }
}
