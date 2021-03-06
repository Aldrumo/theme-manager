# theme-manager
A laravel theme package

### ThemeManager

```php
    $themeCollection = resolve(\Aldrumo\ThemeManager\ThemeManager::class)->availableThemes();
```

```php
    resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme('ThemeName');
```

```php
    // throws \Aldrumo\ThemeManager\Exceptions\ActiveThemeNotSetException
    resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme();
```

```php
    // throws \Aldrumo\ThemeManager\Exceptions\ThemeNotFoundException
    resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme('MissingTheme');
```

```php
    resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme('ThemeName');

    $themeBase = resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme();
```

```php
    // runs uninstall on OldTheme ThemeBase file
    // runs install on NewTheme ThemeBase file
    // runs activeTheme for NewTheme  
    resolve(\Aldrumo\ThemeManager\ThemeManager::class)->installTheme('NewTheme', 'OldTheme');
```

### ThemeBase

```php
    $themeBase = resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme();

    $viewsCollection = $themeBase->availableViews();
```

```php
    $themeBase = resolve(\Aldrumo\ThemeManager\ThemeManager::class)->activeTheme();

    $themeBase->install();
    $themeBase->uninstall();
```
