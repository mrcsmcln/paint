<?php

namespace Paint\Hooks;

class Action extends Hook
{
    protected function add(): void
    {
        add_action(
            $this->tag,
            $this->functionToCall,
            $this->priority,
            $this->acceptedArgs
        );
    }
}
