<?php

namespace Paint\Concerns\Acf;

use Paint\Services\Acf\OptionsPage;

trait AddsOptionsPages
{
    protected $optionsPages = [];

    protected function addOptionsPages()
    {
        foreach ($this->optionsPages as $optionsPage) {
            new OptionsPage(...$this->cleanOptionsPage($optionsPage));
        }
    }

    protected function cleanOptionsPage($data)
    {
        return ! is_array($data) ? [$data] : $data;
    }
}
