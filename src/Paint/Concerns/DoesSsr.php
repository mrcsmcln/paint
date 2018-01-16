<?php

namespace Paint\Concerns;

use Paint\Services\Ssr;

trait DoesSsr
{
    protected $ssrEntry;

    protected $ssrVars = [];

    protected function doSsr(): void
    {
        new Ssr($this->ssrEntry, $this->ssrVars);
    }
}
