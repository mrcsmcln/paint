<?php

namespace Paint\Concerns;

use Paint\Services\NavMenu;

trait RegistersNavMenus
{
    protected $navMenus = [];

    protected function registerNavMenus()
    {
        foreach ($this->navMenus as $navMenu) {
            new NavMenu(...array_wrap($navMenu));
        }
    }
}
