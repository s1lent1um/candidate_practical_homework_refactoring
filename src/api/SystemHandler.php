<?php

namespace Language\api;

class SystemHandler
{
    /** @var Handler  */
    protected $apiHandler;
    /** @var string */
    protected $system;

    public function __construct(Handler $apiHandler, $system)
    {
        $this->system = $system;
        $this->apiHandler = $apiHandler;
    }

    public function call($action, $args)
    {
        return $this->apiHandler->call($this->system, $action, $args);
    }
}
