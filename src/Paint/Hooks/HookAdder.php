<?php

namespace Paint\Hooks;

use Stringy\Stringy;

class HookAdder
{
    protected $hookable;

    public function __construct(Hookable $hookable)
    {
        $this->hookable = $hookable;

        $this->addHooks();
    }

    public function addHooks()
    {
        $this->addActions();
        $this->addFilters();
    }

    public function addActions()
    {
        foreach ($this->hookable->actions as $action) {
            new Action(...$this->cleanAction($action));
        }
    }

    public function addFilters()
    {
        foreach ($this->hookable->filters as $filter) {
            new Filter(...$this->cleanFilter($filter));
        }
    }

    protected function cleanAction($data)
    {
        return $this->cleanHook($data, 'action');
    }

    protected function cleanFilter($data)
    {
        return $this->cleanHook($data, 'filter');
    }

    protected function cleanHook($data, $type)
    {
        $data = ! is_array($data) ? [$data] : $data;

        if (is_string($data[1] ?? null) && ! is_callable($data[1] ?? null)) {
            $data[1] = [$this->hookable, $data[1]];
        }

        if (! is_callable($data[1] ?? null)) {
            array_splice($data, 1, 0, [$this->getFunctionToAdd($data[0], $type)]);
        }

        return $data;
    }

    protected function getFunctionToAdd($tag, $type)
    {
        return [
            $this->hookable,
            str_replace(
                ' ',
                '',
                lcfirst(ucwords(str_replace(['_', '-', '/'], ' ', $tag.'_'.$type)))
            ),
        ];
    }
}
