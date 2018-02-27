<?php

namespace Paint\Services;

use Paint\Hooks\Hookable;

class ImageSize extends Hookable
{
    public $actions = [
        'after_setup_theme',
    ];

    /**
     * Image size identifier.
     *
     * @var string
     */
    protected $name;

    /**
     * Image width in pixels.
     *
     * @var int
     */
    protected $width;

    /**
     * Image height in pixels.
     *
     * @var int
     */
    protected $height;

    /**
     * Whether to crop images to specified width and height or resize.
     * An array can specify positioning of the crop area.
     *
     * @var bool | array
     */
    protected $crop;

    public function __construct(
        string $name,
        int $width,
        int $height = null,
        $crop = false
    ) {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height ?? $width;
        $this->crop = $crop;

        parent::__construct();
    }

    public function afterSetupThemeAction()
    {
        add_image_size($this->name, $this->width, $this->height, $this->crop);
    }
}
