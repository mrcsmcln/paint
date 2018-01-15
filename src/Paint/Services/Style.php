<?php

namespace Paint\Services;

use Paint\Hooks\Hookable;

class Style extends Hookable
{
    public $actions = [
        'wp_enqueue_scripts'
    ];

    protected $handle;

    protected $src;

    protected $deps;

    protected $ver;

    protected $media;

    public function __construct(
        string $handle,
        string $src,
        array $deps = [],
        $ver = false,
        string $media = 'all'
    ) {
        $this->handle = $handle;
        $this->src = $src;
        $this->deps = $deps;
        $this->ver = $ver;
        $this->media = $media;

        parent::__construct();
    }

    public function wpEnqueueScriptsAction()
    {
        wp_enqueue_style(
            $this->handle,
            $this->src,
            $this->deps,
            $this->ver,
            $this->media
        );
    }
}
