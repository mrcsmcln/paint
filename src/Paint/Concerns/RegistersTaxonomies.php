<?php

namespace Paint\Concerns;

use Paint\Services\Taxonomy;

trait RegistersTaxonomies
{
    protected $taxonomies = [];

    protected function registerTaxonomies(): void
    {
        foreach ($this->taxonomies as $taxonomy) {
            new Taxonomy(...$taxonomy);
        }
    }
}
