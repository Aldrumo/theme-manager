<?php

namespace Aldrumo\ThemeManager\Theme;

use Aldrumo\Support\Traits\CanGetPackageName;
use Illuminate\Contracts\Support\Arrayable;

abstract class AvailableTemplate implements Arrayable
{
    use CanGetPackageName;

    /**
     * A short description about the template style
     *
     * @return string
     */
    abstract public function shortDesc(): string;

    /**
     * A screenshot to be used as a "preview"
     *
     * @return string
     */
    abstract public function screenshot(): string;

    /**
     * Path to the blade template
     *
     * @return string
     */
    abstract public function view(): string;

    public function toArray(): array
    {
        return [
            'name'       => studlyToText($this->packageName()),
            'desc'       => $this->shortDesc(),
            'screenshot' => $this->screenshot(),
            'view'       => $this->view(),
        ];
    }
}
