<?php

namespace Paint\Hooks;

class Action extends Hook
{
    protected function add()
    {
        add_action(
            $this->tag,
            $this->functionToCall,
            $this->priority,
            $this->acceptedArgs
        );
    }
}
