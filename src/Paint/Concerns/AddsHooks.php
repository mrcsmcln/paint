<?php

namespace Paint\Concerns;

use Stringy\Stringy;

trait AddsHooks
{
    protected function addHooks(): void
    {
        $this->addActions();
        $this->addFilters();
    }

    protected function addActions(): void
    {
        $actions = ! is_array($this->actions) ? [$this->actions] : $this->actions;

        foreach ($actions as $action) {
            add_action(...$this->parseAction($action));
        }
    }

    protected function addFilters(): void{
        $filters = ! is_array($this->filters) ? [$this->filters] : $this->filters;

        foreach ($filters as $filter) {
            add_filter(...$this->parseFilter($filter));
        }
    }

    protected function parseAction($args): array
    {
        return $this->parseHook('action', $args);
    }

    protected function parseFilter($args): array
    {
        return $this->parseHook('filter', $args);
    }

    protected function parseHook(string $type, $args): array
    {
        $args = ! is_array($args) ? [$args] : $args;

        if (! is_callable($args[1] ?? null)) {
            array_splice($args, 1, 0, [$this->getFunctionToAdd($args[0], $type)]);
        }

        return $args;
    }

    protected function getFunctionToAdd($tag, $type): callable
    {
        return [
            $this,
            (string) Stringy::create($tag.'_'.$type)->camelize(),
        ];
    }
}
