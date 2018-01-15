<?php

namespace Paint\Concerns;

use Paint\Services\Script;

trait EnqueuesScripts
{
    protected $scripts = [];

    protected function enqueueScripts(): void
    {
        foreach ($this->scripts as $script) {
            new script(...$this->cleanScript($script));
        }
    }

    protected function cleanScript($data): array
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
