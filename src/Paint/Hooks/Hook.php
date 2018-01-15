<?php

namespace Paint\Hooks;

abstract class Hook
{
    protected $tag;

    protected $functionToCall;

    protected $priority;

    protected $acceptedArgs;

    public function __construct(
        string $tag,
        callable $functionToCall,
        int $priority = 10,
        int $acceptedArgs = 1
    ) {
        $this->tag = $tag;
        $this->functionToCall = $functionToCall;
        $this->priority = $priority;
        $this->acceptedArgs = $acceptedArgs;

        $this->add();
    }
}
