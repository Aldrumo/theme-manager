<?php

namespace Aldrumo\ThemeManager\Models;

use Aldrumo\ThemeManager\Exceptions\CannotUninstallActiveThemeException;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyActiveException;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyInstalledException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotActiveException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotInstalledException;
use Aldrumo\ThemeManager\Theme\ThemeBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ItemNotFoundException;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActive() : Theme
    {
        return static::where('is_active', 1)->first();
    }

    public function getThemeBase() : ThemeBase
    {
        return app('Theme:' . $this->name);
    }

    public function install(ThemeBase $theme): bool
    {
        $themeName = $theme->packageName('ServiceProvider');

        $model = Theme::where('name', $themeName)->first();
        if ($model !== null) {
            throw new ThemeAlreadyInstalledException();
        }

        $model = new Theme([
            'name'      => $themeName,
            'is_active' => false,
        ]);

        try {
            $model->save();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function uninstall(ThemeBase $theme): bool
    {
        $themeName = $theme->packageName('ServiceProvider');

        $model = Theme::where('name', $themeName)->first();
        if ($model === null) {
            throw new ThemeNotInstalledException();
        }

        if ($model->is_active) {
            throw new CannotUninstallActiveThemeException();
        }

        try {
            $model->delete();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function activate(ThemeBase $theme): bool
    {
        $themeName = $theme->packageName('ServiceProvider');

        $model = Theme::where('name', $themeName)->first();
        if ($model === null) {
            throw new ThemeNotInstalledException();
        }

        if ($model->is_active) {
            throw new ThemeAlreadyActiveException();
        }

        try {
            $model->is_active = true;
            $model->save();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function deactivate(ThemeBase $theme): bool
    {
        $themeName = $theme->packageName('ServiceProvider');

        $model = Theme::where('name', $themeName)->first();
        if ($model === null) {
            throw new ThemeNotInstalledException();
        }

        if (! $model->is_active) {
            throw new ThemeNotActiveException();
        }

        try {
            $model->is_active = false;
            $model->save();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
