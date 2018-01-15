<?php

namespace Paint\Concerns;

use Paint\Services\Feature;

trait AddsFeatures
{
    protected $features = [];

    protected function addFeatures(): void
    {
        foreach ($this->features as $feature) {
            new Feature(...$this->cleanFeature($feature));
        }
    }

    protected function cleanFeature($data): array
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
