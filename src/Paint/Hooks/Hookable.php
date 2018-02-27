<?php

namespace Paint\Hooks;

abstract class Hookable
{
    public $actions = [];

    public $filters = [];

    public function __construct()
    {
        $this->addHooks();
    }

    protected function addHooks()
    {
        new HookAdder($this);
    }
}
