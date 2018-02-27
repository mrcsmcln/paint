<?php

namespace Paint\Concerns;

use Paint\Services\Style;

trait EnqueuesStyles
{
    protected $styles = [];

    protected function enqueueStyles()
    {
        foreach ($this->styles as $style) {
            new Style(...$this->cleanStyle($style));
        }
    }

    protected function cleanStyle($data)
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
