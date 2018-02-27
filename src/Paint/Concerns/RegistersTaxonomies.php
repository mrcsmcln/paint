<?php

namespace Paint\Concerns;

use Paint\Services\Taxonomy;

trait RegistersTaxonomies
{
    protected $taxonomies = [];

    protected function registerTaxonomies()
    {
        foreach ($this->taxonomies as $taxonomy) {
            new Taxonomy(...$taxonomy);
        }
    }
}
