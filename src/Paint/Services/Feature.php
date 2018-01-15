<?php

namespace Paint\Services;

use Paint\Hooks\Hookable;

class Feature extends Hookable
{
    public $actions = [
        'after_setup_theme',
    ];

    /**
     * The feature being added. Likely core values include 'post-formats',
     * 'post-thumbnails', 'html5', 'custom-logo', 'custom-header-uploads',
     * 'custom-header', 'custom-background', 'title-tag', 'starter-content', etc.
     *
     * @var string
     */
    protected $feature;

    /**
     * Extra arguments to pass along with certain features.
     *
     * @var mixed
     */
    protected $args;

    /**
     *
     *
     *
     */
    public function __construct(string $feature, ...$args)
    {
        $this->feature = $feature;
        $this->args = $args;

        parent::__construct();
    }

    /**
     * Function to call for the after_setup_theme action.
     *
     * @return void
     */
    public function afterSetupThemeAction()
    {
        add_theme_support($this->feature, ...$this->args);
    }
}
