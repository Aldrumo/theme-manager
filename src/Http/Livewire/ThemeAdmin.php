<?php

namespace Aldrumo\ThemeManager\Http\Livewire;

use Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException;
use Aldrumo\ThemeManager\Exceptions\CannotActivateTheme;
use Aldrumo\ThemeManager\Exceptions\CannotUninstallActiveThemeException;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyActiveException;
use Aldrumo\ThemeManager\Exceptions\ThemeAlreadyInstalledException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotActiveException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotInstalledException;
use Aldrumo\ThemeManager\Exceptions\ThemeNotUninstalledException;
use Aldrumo\ThemeManager\Models\Theme;
use Aldrumo\ThemeManager\ThemeManager;
use Livewire\Component;

class ThemeAdmin extends Component
{
    public function render(ThemeManager $manager)
    {
        return view(
            'ThemeManager::livewire.theme-admin',
            [
                'themes' => $manager->discoverThemes(),
            ]
        );
    }

    public function installTheme(ThemeManager $manager, string $themeName)
    {
        try {
            $theme = $manager->installTheme($themeName);
        } catch (ThemeNotFoundException $e) {
            session()->flash(
                'error',
                __('Something went wrong, we couldn\'t find the theme to install.')
            );
            return;
        } catch (ThemeAlreadyInstalledException $e) {
            session()->flash(
                'error',
                __('The theme is already installed.')
            );
            return;
        } catch (ThemeNotInstalledException | \Exception $e) {
            session()->flash(
                'error',
                __('There was a problem installing the theme.')
            );
            return;
        }

        session()->flash(
            'success',
            __('Theme installed successfully.')
        );
    }

    public function uninstallTheme(ThemeManager $manager, string $themeName)
    {
        try {
            $theme = $manager->uninstallTheme($themeName);
        } catch (ThemeNotFoundException $e) {
            session()->flash(
                'error',
                __('Something went wrong, we couldn\'t find the theme to uninstall.')
            );
            return;
        } catch (CannotUninstallActiveThemeException $e) {
            session()->flash(
                'error',
                __('There was a problem, you cannot uninstall the active theme.')
            );
            return;
        } catch (ThemeNotUninstalledException | \Exception $e) {
            session()->flash(
                'error',
                __('There was a problem uninstalling the theme.')
            );
            return;
        }

        session()->flash(
            'success',
            __('Theme uninstalled successfully.')
        );
    }

    public function activateTheme(ThemeManager $manager, string $themeName)
    {
        try {
            $currentTheme = $manager->activeTheme();
            $currentThemeName = $currentTheme->packageName('ServiceProvider');
        } catch (ActiveThemeNotSetException $e) {
            $currentThemeName = null;
        }

        try {
            $theme = $manager->activateTheme($themeName, $currentThemeName);
        } catch (ThemeNotFoundException $e) {
            session()->flash(
                'error',
                __('Something went wrong, we couldn\'t find the theme to activate.')
            );
            return;
        } catch (ThemeAlreadyActiveException $e) {
            session()->flash(
                'error',
                __('Could not activate theme, theme is already active.')
            );
            return;
        } catch (ThemeNotActiveException $e) {
            session()->flash(
                'error',
                __('Could not deactivate theme, theme is not active.')
            );
            return;
        } catch (CannotActivateTheme | CannotDeactivateOldTheme $e) {
            session()->flash(
                'error',
                __('There was a problem activating theme.')
            );
            return;
        }

        session()->flash(
            'success',
            __('Theme set as active site theme.')
        );
    }
}
