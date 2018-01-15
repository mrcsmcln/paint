<?php

namespace Paint\Concerns;

use Paint\Services\Ssr;

trait DoesSsr
{
    protected function doSsr($entry): void
    {
        new Ssr($entry);
    }
}
