<?php

namespace Paint\Concerns;

use Paint\Services\Style;

trait EnqueuesStyles
{
    protected $styles = [];

    protected function enqueueStyles(): void
    {
        foreach ($this->styles as $style) {
            new Style(...$this->cleanStyle($style));
        }
    }

    protected function cleanStyle($data): array
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
