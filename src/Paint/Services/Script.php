<?php

namespace Paint\Services;

use Paint\Hooks\Hookable;

class Script extends Hookable
{
    public $actions = [
        'wp_enqueue_scripts'
    ];

    protected $handle;

    protected $src;

    protected $deps;

    protected $ver;

    protected $inFooter;

    public function __construct(
        string $handle,
        string $src,
        array $deps = [],
        $ver = false,
        bool $inFooter = false
    ) {
        $this->handle = $handle;
        $this->src = $src;
        $this->deps = $deps;
        $this->ver = $ver;
        $this->inFooter = $inFooter;

        parent::__construct();
    }

    public function wpEnqueueScriptsAction()
    {
        wp_enqueue_script(
            $this->handle,
            $this->src,
            $this->deps,
            $this->ver,
            $this->inFooter
        );
    }
}
