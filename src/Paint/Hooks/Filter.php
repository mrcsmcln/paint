<?php

namespace Paint\Hooks;

class Filter extends Hook
{
    protected function add()
    {
        add_filter(
            $this->tag,
            $this->functionToCall,
            $this->priority,
            $this->acceptedArgs
        );
    }
}
