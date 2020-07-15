<?php

namespace Aldrumo\ThemeManager;

use Illuminate\Contracts\Cache\Repository;

class ThemeManager
{
    /** @var Repository */
    protected $cache;

    /**
     * ThemeManager constructor.
     * @param Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }
}
