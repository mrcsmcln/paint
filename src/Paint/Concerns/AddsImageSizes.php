<?php

namespace Paint\Concerns;

use Paint\Services\ImageSize;

trait AddsImageSizes
{
    protected function addImageSizes(): void
    {
        foreach ($this->imageSizes as $imageSize) {
            new ImageSize(...$imageSize);
        }
    }
}
