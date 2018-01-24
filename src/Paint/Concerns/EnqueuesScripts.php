<?php

namespace Paint\Concerns;

use Paint\Services\Script;

trait EnqueuesScripts
{
    protected $scripts = [];

    protected function enqueueScripts(): void
    {
        foreach ($this->scripts as $script) {
            new Script(...array_wrap($script));
        }
    }
}
