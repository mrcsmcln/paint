<?php

namespace Paint\Concerns;

use Paint\Services\PostType;

trait RegistersPostTypes
{
    protected $postTypes = [];

    protected function registerPostTypes()
    {
        foreach ($this->postTypes as $postType) {
            new PostType(...$this->cleanPostType($postType));
        }
    }

    protected function cleanPostType($data)
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
