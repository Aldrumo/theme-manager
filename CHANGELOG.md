# Changelog

## Unreleased

* Theme Admin

## [0.4.0 - 2021-06-29](https://github.com/Aldrumo/theme-manager/releases/tag/0.4.0)

* Added install / uninstall methods to ThemeBase file. These can be used by your themes to auto publish and remove assets as a theme is installed
* Added installTheme / uninstallTheme method to ThemeManager to run the install / uninstall methods on ThemeBase files
* Added activate / deactivate methods to ThemeBase file. These can be used by your theme to auto publish / remove assets when a theme is set as active
* Added activateTheme method to ThemeManager to run the activate / deactivate methods on ThemeBase files and set the theme as active.
* Added `themeView()` helper method to replace `view()`. This simply auto prepends the active theme to the set view.

## [0.3.0 - 2021-04-03](https://github.com/Aldrumo/theme-manager/releases/tag/0.3.0)

* Added render methods to exceptions, powered by a config to define view path. [Part of Aldrumo/Core#10](https://github.com/Aldrumo/core/issues/10)

## [0.2.0 - 2021-02-26](https://github.com/Aldrumo/theme-manager/releases/tag/0.2.0)

* Removed dependency of Aldrumo Theme Installer. Part of [Aldrumo/Core#1](https://github.com/Aldrumo/core/issues/1)

## [0.1.0 - 2021-01-29](https://github.com/Aldrumo/theme-manager/releases/tag/0.1.0w)

Internal development release of the Theme Manager
