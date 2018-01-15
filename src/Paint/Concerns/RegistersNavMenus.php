<?php

namespace Paint\Concerns;

use Paint\Services\NavMenu;

trait RegistersNavMenus
{
    protected $navMenus = [];

    protected function registerNavMenus(): void
    {
        foreach ($this->navMenus as $navMenu) {
            new NavMenu(...$this->cleanNavMenu($navMenu));
        }
    }

    protected function cleanNavMenu($data): array
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
