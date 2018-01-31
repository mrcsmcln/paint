<?php

namespace Paint\Services\Acf;

use Paint\Hooks\Hookable;

class OptionsPage extends Hookable
{
    public $actions = ['admin_menu'];

    protected $args = [];

    protected $data;

    public function __construct(...$args)
    {
        if (is_string($args[0])) {
            $this->args['page_title'] = array_shift($args);
        }

        if (is_string($args[0]) ?? null) {
            $this->args['parent_slug'] = array_shift($args);
        }

        if (is_array($args[0] ?? null)) {
            $this->args = array_merge($this->args, array_shift($args));
        }

        parent::__construct();
    }

    public function adminMenuAction()
    {
        $this->data = acf_add_options_page($this->args);
    }
}
