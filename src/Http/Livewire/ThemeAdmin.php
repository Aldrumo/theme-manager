<?php

namespace Aldrumo\ThemeManager\Http\Livewire;

use Aldrumo\ThemeManager\Models\Theme;
use Aldrumo\ThemeManager\ThemeManager;
use Livewire\Component;

class ThemeAdmin extends Component
{
    public $themes;

    public function mount(ThemeManager $manager)
    {
        $this->themes = $manager->discoverThemes();
    }

    public function render()
    {
        return view('ThemeManager::livewire.theme-admin');
    }
}
