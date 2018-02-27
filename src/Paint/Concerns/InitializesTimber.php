<?php

namespace Paint\Concerns;

use Paint\Services\Timber;

trait InitializesTimber
{
    protected function initializeTimber()
    {
        new Timber;
    }
}
