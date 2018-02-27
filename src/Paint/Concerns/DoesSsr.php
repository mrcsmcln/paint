<?php

namespace Paint\Concerns;

use Paint\Services\Ssr;

trait DoesSsr
{
    protected $ssrEntry;

    protected $ssrVars = [];

    protected function doSsr()
    {
        new Ssr($this->ssrEntry, $this->ssrVars);
    }
}
