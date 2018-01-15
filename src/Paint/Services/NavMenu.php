<?php

namespace Paint\Services;

use Stringy\Stringy;
use Paint\Hooks\Hookable;

class NavMenu extends Hookable
{
    public $actions = [
        'after_setup_theme',
    ];

    /**
     * Menu location identifier, like a slug.
     *
     * @var string $location
     */
    protected $location;

    /**
     *  Menu description - for identifying the menu in the dashboard.
     *
     * @var string $description
     */
    protected $description;

    public function __construct(string $location, ?string $description = null)
    {
        $this->location = $location;
        $this->description = $description ?? __(
            Stringy::create($location)->delimit(' ')->toTitleCase()
        );

        parent::__construct();
    }

    public function afterSetupThemeAction(): void
    {
        register_nav_menu($this->location, $this->description);
    }
}
